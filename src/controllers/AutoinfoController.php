<?php
namespace App\Controllers;

class AutoinfoController {
    private $view;
    
    public function __construct($view) {
        $this->view = $view;
    }

    public function index() {
        return $this->view->render('autoinfo.twig');
    }
}