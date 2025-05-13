<?php
namespace App\Controllers;

use App\Models\CarModel;

class HomeController {
    private $view;
    private $carModel;

    public function __construct($view, CarModel $carModel) {
        $this->view = $view;
        $this->carModel = $carModel;
    }

    public function index() {
        session_start();
        $user = $_SESSION['user'] ?? null;

        $cars = $this->carModel->getAllCars();
        return $this->view->render('home.twig', ['cars' => $cars, 'user' => $user]);
    }
}