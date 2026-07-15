<?php

namespace Komfort\App\Controllers;

use Komfort\App\Middleware\Csrf;
use Komfort\App\Models\Destination;
use Komfort\App\Models\ServiceType;

class HomeController extends BaseController
{
    public function index(): void
    {
        $destinationModel = new Destination();
        $serviceTypeModel = new ServiceType();
        
        $destinations = $destinationModel->getActive();
        $serviceTypes = $serviceTypeModel->getActive();
        
        $this->view('home', [
            'title' => 'Home - Komfort Tours & Travel',
            'destinations' => array_slice($destinations, 0, 6),
            'serviceTypes' => $serviceTypes
        ]);
    }

    public function about(): void
    {
        $this->view('about', [
            'title' => 'About Us - Komfort Tours & Travel'
        ]);
    }

    public function contact(): void
    {
        $this->view('contact', [
            'title' => 'Contact Us - Komfort Tours & Travel',
            'csrf_token' => Csrf::generateToken()
        ]);
    }

    public function services(): void
    {
        $serviceTypeModel = new ServiceType();
        $serviceTypes = $serviceTypeModel->getActive();
        
        $this->view('services', [
            'title' => 'Our Services - Komfort Tours & Travel',
            'serviceTypes' => $serviceTypes
        ]);
    }
}
