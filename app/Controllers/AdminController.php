<?php

namespace Komfort\App\Controllers;

use Komfort\App\Middleware\Auth;
use Komfort\App\Middleware\Csrf;
use Komfort\App\Models\Booking;
use Komfort\App\Models\User;
use Komfort\App\Models\Destination;
use Komfort\App\Models\Vehicle;
use Komfort\App\Models\Payment;
use Komfort\Config\Logger as Log;

class AdminController extends BaseController
{
    public function __construct()
    {
        Auth::requireAdmin();
        Auth::checkSessionTimeout();
    }

    public function dashboard(): void
    {
        $bookingModel = new Booking();
        $userModel = new User();
        $vehicleModel = new Vehicle();
        $paymentModel = new Payment();
        
        $totalBookings = $bookingModel->count();
        $totalUsers = $userModel->count();
        $totalVehicles = $vehicleModel->count();
        $totalRevenue = $paymentModel->getTotalRevenue();
        
        $recentBookings = $bookingModel->getRecent(10);
        $pendingBookings = $bookingModel->getByStatus('pending');
        
        $this->view('admin.dashboard', [
            'title' => 'Admin Dashboard - Komfort Tours & Travel',
            'totalBookings' => $totalBookings,
            'totalUsers' => $totalUsers,
            'totalVehicles' => $totalVehicles,
            'totalRevenue' => $totalRevenue,
            'recentBookings' => $recentBookings,
            'pendingBookings' => $pendingBookings
        ]);
    }

    public function bookings(): void
    {
        $bookingModel = new Booking();
        $bookings = $bookingModel->all();
        
        $this->view('admin.bookings', [
            'title' => 'Manage Bookings - Admin',
            'bookings' => $bookings
        ]);
    }

    public function users(): void
    {
        $userModel = new User();
        $users = $userModel->all();
        
        $this->view('admin.users', [
            'title' => 'Manage Users - Admin',
            'users' => $users
        ]);
    }

    public function destinations(): void
    {
        $destinationModel = new Destination();
        $destinations = $destinationModel->all();
        
        $this->view('admin.destinations', [
            'title' => 'Manage Destinations - Admin',
            'destinations' => $destinations,
            'csrf_token' => Csrf::generateToken()
        ]);
    }

    public function vehicles(): void
    {
        $vehicleModel = new Vehicle();
        $vehicles = $vehicleModel->all();
        
        $this->view('admin.vehicles', [
            'title' => 'Manage Vehicles - Admin',
            'vehicles' => $vehicles,
            'csrf_token' => Csrf::generateToken()
        ]);
    }

    public function confirmBooking(int $id): void
    {
        $bookingModel = new Booking();
        
        try {
            $bookingModel->confirm($id);
            Log::info('Booking confirmed by admin', ['booking_id' => $id]);
            $this->setFlash('success', 'Booking confirmed successfully.');
            $this->redirect('/admin/bookings');
        } catch (\Exception $e) {
            Log::error('Booking confirmation failed', ['error' => $e->getMessage()]);
            $this->setFlash('error', 'Confirmation failed. Please try again.');
            $this->back();
        }
    }

    public function cancelBooking(int $id): void
    {
        $bookingModel = new Booking();
        
        try {
            $bookingModel->cancel($id);
            Log::info('Booking cancelled by admin', ['booking_id' => $id]);
            $this->setFlash('success', 'Booking cancelled successfully.');
            $this->redirect('/admin/bookings');
        } catch (\Exception $e) {
            Log::error('Booking cancellation failed', ['error' => $e->getMessage()]);
            $this->setFlash('error', 'Cancellation failed. Please try again.');
            $this->back();
        }
    }

    public function toggleUserStatus(int $id): void
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->setFlash('error', 'User not found.');
            $this->redirect('/admin/users');
            return;
        }

        try {
            if ($user['is_active']) {
                $userModel->deactivate($id);
                Log::info('User deactivated by admin', ['user_id' => $id]);
                $this->setFlash('success', 'User deactivated successfully.');
            } else {
                $userModel->activate($id);
                Log::info('User activated by admin', ['user_id' => $id]);
                $this->setFlash('success', 'User activated successfully.');
            }
            $this->redirect('/admin/users');
        } catch (\Exception $e) {
            Log::error('User status toggle failed', ['error' => $e->getMessage()]);
            $this->setFlash('error', 'Operation failed. Please try again.');
            $this->back();
        }
    }
}
?>