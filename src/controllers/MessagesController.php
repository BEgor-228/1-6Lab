<?php
namespace App\Controllers;

use App\Models\MessagesModel;

class MessagesController {
    private $view;
    private $messagesModel;

    public function __construct($view, MessagesModel $messagesModel) {
        $this->view = $view;
        $this->messagesModel = $messagesModel;
    }

    public function handleForm($request) {
        $car_brand = $request->get('car_brand', '');
        $car_model = $request->get('car_model', '');
        $car_color = $request->get('car_color', '');
        $car_price = $request->get('car_price', '');
        $client_name = $request->get('client_name', '');
        $client_phone = $request->get('client_phone', '');

        $errors = [];

        if (empty($car_brand) || empty($car_model) || empty($car_color) || empty($car_price)) {
            $errors[] = "Все поля автомобиля должны быть заполнены.";
        }
        if (empty($client_name)) {
            $errors[] = "Имя клиента обязательно.";
        }
        if (empty($client_phone)) {
            $errors[] = "Телефон клиента обязателен.";
        }

        if (!preg_match("/^[a-zA-Zа-яА-Я\s\-]+$/u", $client_name)) {
            $errors[] = "Имя клиента может содержать только буквы, пробелы и дефисы.";
        }
        if (!preg_match("/^(\+7|8)\d{10}$/", $client_phone)) {
            $errors[] = "Телефон должен быть в формате +7XXXXXXXXXX или 8XXXXXXXXXX.";
        }

        $car = $this->messagesModel->checkCarAvailability($car_brand, $car_model, $car_color, $car_price);
        if (!$car) {
            $errors[] = "Выбранный автомобиль не найден в базе данных.";
        } elseif ($car['stock_quantity'] <= 0) {
            $errors[] = "Выбранный автомобиль отсутствует на складе.";
        }

        if (!empty($errors)) {
            return $this->view->render('messages.twig', ['errors' => $errors]);
        }

        try {
            $this->messagesModel->insertSale($car_brand, $car_model, $car_color, $car_price, $client_name, $client_phone);
            $this->messagesModel->updateStock($car_brand, $car_model, $car_color, $car_price);
            return $this->view->render('messages.twig', ['success' => "Заказ успешно оформлен!"]);
        } catch (\Exception $e) {
            return $this->view->render('messages.twig', ['errors' => ["Ошибка при обработке заказа: " . $e->getMessage()]]);
        }
    }
}