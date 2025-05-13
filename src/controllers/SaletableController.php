<?php
namespace App\Controllers;

use App\Models\SaletableModel;

class SaletableController {
    private $view;
    private $saletableModel;

    public function __construct($view, SaletableModel $saletableModel) {
        $this->view = $view;
        $this->saletableModel = $saletableModel;
    }

    public function index() {
        $sales = $this->saletableModel->getAllSales();
        return $this->view->render('saleTable.twig', ['sales' => $sales]);
    }
}