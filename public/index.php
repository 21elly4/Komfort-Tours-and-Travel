<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Komfort\Config\App;
use Komfort\Config\Database;
use Komfort\Config\Logger as Log;

try {
    App::load();
    
    if (App::isDebug()) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    } else {
        ini_set('display_errors', 0);
        error_reporting(0);
    }
    
    session_start();
    
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    
    $routes = [
        'GET' => [
            '/' => 'HomeController@index',
            '/about' => 'HomeController@about',
            '/contact' => 'HomeController@contact',
            '/services' => 'HomeController@services',
            '/login' => 'AuthController@showLogin',
            '/register' => 'AuthController@showRegister',
            '/logout' => 'AuthController@logout',
            '/dashboard' => 'DashboardController@index',
            '/dashboard/profile' => 'DashboardController@profile',
            '/booking/create' => 'BookingController@create',
            '/booking/show/(\d+)' => 'BookingController@show',
            '/admin/dashboard' => 'AdminController@dashboard',
            '/admin/bookings' => 'AdminController@bookings',
            '/admin/users' => 'AdminController@users',
            '/admin/destinations' => 'AdminController@destinations',
            '/admin/vehicles' => 'AdminController@vehicles',
        ],
        'POST' => [
            '/login' => 'AuthController@login',
            '/register' => 'AuthController@register',
            '/logout' => 'AuthController@logout',
            '/dashboard/profile' => 'DashboardController@updateProfile',
            '/booking/create' => 'BookingController@store',
            '/booking/cancel/(\d+)' => 'BookingController@cancel',
            '/admin/booking/confirm/(\d+)' => 'AdminController@confirmBooking',
            '/admin/booking/cancel/(\d+)' => 'AdminController@cancelBooking',
            '/admin/user/toggle/(\d+)' => 'AdminController@toggleUserStatus',
        ]
    ];
    
    $matchedRoute = null;
    $params = [];
    
    foreach ($routes[$method] as $pattern => $handler) {
        $regexPattern = '#^' . str_replace(['(\d+)'], ['(\d+)'], $pattern) . '$#';
        if (preg_match($regexPattern, $uri, $matches)) {
            $matchedRoute = $handler;
            array_shift($matches);
            $params = $matches;
            break;
        }
    }
    
    if ($matchedRoute) {
        [$controllerName, $action] = explode('@', $matchedRoute);
        
        $controllerClass = "Komfort\\App\\Controllers\\{$controllerName}";
        $controller = new $controllerClass();
        
        if (!empty($params)) {
            $controller->$action(...$params);
        } else {
            $controller->$action();
        }
    } else {
        http_response_code(404);
        echo '<h1>404 - Page Not Found</h1>';
    }
    
} catch (\Exception $e) {
    Log::error('Application error', [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
    
    if (App::isDebug()) {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        echo '<h1>500 - Internal Server Error</h1>';
    }
}
