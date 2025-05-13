<?php
namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class FunctionsController {
    private $view;
    private $carSalesModel;

    public function __construct($view, $carSalesModel) {
        $this->view = $view;
        $this->carSalesModel = $carSalesModel;
    }

    public function myOrders() {
        session_start();
        if (!isset($_SESSION['user'])) {
            return new Response('Вы не авторизованы. <a href="/entrance">Войти</a>', Response::HTTP_FORBIDDEN);
        }

        $userPhone = $_SESSION['user']['phone'];
        $orders = $this->carSalesModel->getOrdersByPhone($userPhone);

        return $this->view->render('my-orders.twig', ['orders' => $orders]);
    }

    public function downloadReport($format) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            return new Response('Доступ запрещен.', Response::HTTP_FORBIDDEN);
        }

        // Пока что ничего не делаем
        return new Response("Скачивание отчета в формате $format пока не реализовано.");
    }
}