<?php

namespace Komfort\App\Controllers;

use Komfort\App\Models\User;
use Komfort\App\Middleware\Auth;
use Komfort\App\Middleware\Csrf;
use Komfort\Config\Logger as Log;

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.login', [
            'title' => 'Login - Komfort Tours & Travel',
            'csrf_token' => Csrf::generateToken()
        ]);
    }

    public function login(): void
    {
        if (!Csrf::validateRequest()) {
            $this->setFlash('error', 'Invalid security token. Please try again.');
            $this->back();
        }

        $credentials = $this->only(['email', 'password']);
        
        $errors = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ], $credentials);

        if (!empty($errors)) {
            $this->setFlash('error', 'Please fix the errors below.');
            $this->view('auth.login', [
                'title' => 'Login - Komfort Tours & Travel',
                'errors' => $errors,
                'old' => $credentials,
                'csrf_token' => Csrf::generateToken()
            ]);
            return;
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if ($user['role'] === 'admin') {
                $this->redirect('/admin/dashboard');
            } else {
                $this->redirect('/dashboard');
            }
        } else {
            $this->setFlash('error', 'Invalid credentials or account not active.');
            $this->view('auth.login', [
                'title' => 'Login - Komfort Tours & Travel',
                'old' => $credentials,
                'csrf_token' => Csrf::generateToken()
            ]);
        }
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth.register', [
            'title' => 'Register - Komfort Tours & Travel',
            'csrf_token' => Csrf::generateToken()
        ]);
    }

    public function register(): void
    {
        if (!Csrf::validateRequest()) {
            $this->setFlash('error', 'Invalid security token. Please try again.');
            $this->back();
        }

        $data = $this->only(['first_name', 'last_name', 'email', 'phone', 'password', 'password_confirmation']);
        
        $errors = $this->validate([
            'first_name' => ['required', 'min:2'],
            'last_name' => ['required', 'min:2'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed']
        ], $data);

        if (!empty($errors)) {
            $this->setFlash('error', 'Please fix the errors below.');
            $this->view('auth.register', [
                'title' => 'Register - Komfort Tours & Travel',
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => Csrf::generateToken()
            ]);
            return;
        }

        $userModel = new User();
        
        if ($userModel->findByEmail($data['email'])) {
            $this->setFlash('error', 'An account with this email already exists.');
            $this->view('auth.register', [
                'title' => 'Register - Komfort Tours & Travel',
                'old' => $data,
                'csrf_token' => Csrf::generateToken()
            ]);
            return;
        }

        try {
            $userData = [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'password' => $data['password'],
                'role' => 'traveler',
                'is_active' => true
            ];

            $userId = $userModel->create($userData);
            
            Auth::login($userId);
            
            Log::info('New user registered', ['user_id' => $userId, 'email' => $userData['email']]);
            
            $this->setFlash('success', 'Registration successful! Welcome to Komfort Tours & Travel.');
            $this->redirect('/dashboard');
            
        } catch (\Exception $e) {
            Log::error('Registration failed', ['error' => $e->getMessage()]);
            $this->setFlash('error', 'Registration failed. Please try again.');
            $this->view('auth.register', [
                'title' => 'Register - Komfort Tours & Travel',
                'old' => $data,
                'csrf_token' => Csrf::generateToken()
            ]);
        }
    }

    public function logout(): void
    {
        Auth::logout();
        $this->setFlash('success', 'You have been logged out successfully.');
        $this->redirect('/login');
    }
}
