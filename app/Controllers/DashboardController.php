<?php

namespace Komfort\App\Controllers;

use Komfort\App\Middleware\Auth;
use Komfort\App\Middleware\Csrf;
use Komfort\App\Models\Booking;
use Komfort\App\Models\User;
use Komfort\Config\Logger;

class DashboardController extends BaseController
{
    public function __construct()
    {
        Auth::requireAuth();
        Auth::checkSessionTimeout();
    }

    public function index(): void
    {
        $user = Auth::user();
        $bookingModel = new Booking();
        
        $recentBookings = $bookingModel->getByUser($user['id']);
        $upcomingBookings = array_filter($recentBookings, function($booking) {
            return in_array($booking['status'], ['pending', 'confirmed', 'paid']) 
                   && strtotime($booking['start_date']) >= strtotime(date('Y-m-d'));
        });
        
        $this->view('dashboard.index', [
            'title' => 'Dashboard - Komfort Tours & Travel',
            'user' => $user,
            'recentBookings' => array_slice($recentBookings, 0, 5),
            'upcomingBookings' => array_values($upcomingBookings),
            'csrf_token' => Csrf::generateToken()
        ]);
    }

    public function bookings(): void
    {
        Auth::requireAuth();
        
        $user = Auth::user();
        $bookingModel = new Booking();
        $bookings = $bookingModel->getByUser($user['id']);
        
        $this->view('dashboard.bookings', [
            'title' => 'My Bookings - Komfort Tours & Travel',
            'user' => $user,
            'bookings' => $bookings
        ]);
    }

    public function profile(): void
    {
        Auth::requireAuth();
        
        $user = Auth::user();
        
        $this->view('dashboard.profile', [
            'title' => 'My Profile - Komfort Tours & Travel',
            'user' => $user,
            'csrf_token' => Csrf::generateToken()
        ]);
    }

    public function updateProfile(): void
    {
        Auth::requireAuth();
        
        if (!Csrf::validateRequest()) {
            $this->setFlash('error', 'Invalid security token. Please try again.');
            $this->back();
        }

        $user = Auth::user();
        $data = $this->only(['first_name', 'last_name', 'phone']);
        
        $errors = $this->validate([
            'first_name' => ['required', 'min:2'],
            'last_name' => ['required', 'min:2']
        ], $data);

        if (!empty($errors)) {
            $this->setFlash('error', 'Please fix the errors below.');
            $this->view('dashboard.profile', [
                'title' => 'My Profile - Komfort Tours & Travel',
                'user' => $user,
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => Csrf::generateToken()
            ]);
            return;
        }

        try {
            $userModel = new User();
            $userModel->update($user['id'], $data);
            
            Log::info('User profile updated', ['user_id' => $user['id']]);
            
            $this->setFlash('success', 'Profile updated successfully.');
            $this->redirect('/dashboard/profile');
            
        } catch (\Exception $e) {
            Log::error('Profile update failed', ['error' => $e->getMessage()]);
            $this->setFlash('error', 'Profile update failed. Please try again.');
            $this->back();
        }
    }
}
