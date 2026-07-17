<?php

namespace Komfort\App\Controllers;

use Komfort\App\Middleware\Auth;
use Komfort\App\Middleware\Csrf;
use Komfort\App\Models\Booking;
use Komfort\App\Models\ServiceType;
use Komfort\App\Models\Destination;
use Komfort\Config\Database;
use Komfort\Config\Logger;

class BookingController extends BaseController
{
    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkSessionTimeout();
    }

    public function create(): void
    {
        $serviceTypeModel = new ServiceType();
        $destinationModel = new Destination();
        
        $serviceTypes = $serviceTypeModel->getActive();
        $destinations = $destinationModel->getActive();
        
        $this->view('booking.create', [
            'title' => 'Book a Trip - Komfort Tours & Travel',
            'serviceTypes' => $serviceTypes,
            'destinations' => $destinations,
            'csrf_token' => Csrf::generateToken()
        ]);
    }

    public function store(): void
    {
        if (!Csrf::validateRequest()) {
            $this->setFlash('error', 'Invalid security token. Please try again.');
            $this->back();
        }

        $user = Auth::user();
        $data = $this->only([
            'service_type_id',
            'destination_id',
            'start_date',
            'end_date',
            'number_of_travelers',
            'special_requirements',
            'pickup_location',
            'dropoff_location'
        ]);
        
        $errors = $this->validate([
            'service_type_id' => ['required'],
            'start_date' => ['required'],
            'number_of_travelers' => ['required']
        ], $data);

        if (!empty($errors)) {
            $this->setFlash('error', 'Please fix the errors below.');
            $this->back();
            return;
        }

        try {
            Database::beginTransaction();
            
            $serviceTypeModel = new ServiceType();
            $serviceType = $serviceTypeModel->find($data['service_type_id']);
            
            if (!$serviceType) {
                throw new \Exception('Invalid service type');
            }

            $totalAmount = $serviceType['base_price'] * $data['number_of_travelers'];
            
            $bookingData = [
                'user_id' => $user['id'],
                'service_type_id' => $data['service_type_id'],
                'destination_id' => $data['destination_id'] ?? null,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'number_of_travelers' => $data['number_of_travelers'],
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'special_requirements' => $data['special_requirements'] ?? null,
                'pickup_location' => $data['pickup_location'] ?? null,
                'dropoff_location' => $data['dropoff_location'] ?? null,
            ];

            $bookingModel = new Booking();
            $bookingId = $bookingModel->create($bookingData);
            
            Database::commit();
            
            Logger::info('Booking created', [
                'booking_id' => $bookingId,
                'user_id' => $user['id'],
                'amount' => $totalAmount
            ]);
            
            $this->setFlash('success', 'Booking created successfully! Reference: ' . $bookingModel->find($bookingId)['booking_reference']);
            $this->redirect('/dashboard');
            
        } catch (\Exception $e) {
            Database::rollback();
            Logger::error('Booking creation failed', ['error' => $e->getMessage()]);
            $this->setFlash('error', 'Booking failed. Please try again.');
            $this->back();
        }
    }

    public function show(int $id): void
    {
        $user = Auth::user();
        $bookingModel = new Booking();
        
        $booking = $bookingModel->getWithDetails($id);
        
        if (!$booking || $booking['user_id'] != $user['id']) {
            $this->setFlash('error', 'Booking not found.');
            $this->redirect('/dashboard');
            return;
        }
        
        $this->view('booking.show', [
            'title' => 'Booking Details - Komfort Tours & Travel',
            'booking' => $booking
        ]);
    }

    public function cancel(int $id): void
    {
        $user = Auth::user();
        $bookingModel = new Booking();
        
        $booking = $bookingModel->find($id);
        
        if (!$booking || $booking['user_id'] != $user['id']) {
            $this->setFlash('error', 'Booking not found.');
            $this->redirect('/dashboard');
            return;
        }

        if (!in_array($booking['status'], ['pending', 'confirmed'])) {
            $this->setFlash('error', 'Cannot cancel this booking.');
            $this->redirect('/dashboard');
            return;
        }

        try {
            $bookingModel->cancel($id);
            
            Logger::info('Booking cancelled', ['booking_id' => $id, 'user_id' => $user['id']]);
            
            $this->setFlash('success', 'Booking cancelled successfully.');
            $this->redirect('/dashboard');
            
        } catch (\Exception $e) {
            Logger::error('Booking cancellation failed', ['error' => $e->getMessage()]);
            $this->setFlash('error', 'Cancellation failed. Please try again.');
            $this->back();
        }
    }
}
