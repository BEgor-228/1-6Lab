<?php
namespace App\Models;

use PDO;

class MessagesModel {
    private $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function checkCarAvailability($car_brand, $car_model, $car_color, $car_price) {
        $stmt = $this->pdo->prepare("SELECT stock_quantity FROM cars_inventory WHERE brand = :brand AND model = :model AND color = :color AND price = :price");
        $stmt->execute([
            ':brand' => $car_brand,
            ':model' => $car_model,
            ':color' => $car_color,
            ':price' => $car_price
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertSale($car_brand, $car_model, $car_color, $car_price, $client_name, $client_phone) {
        $stmt = $this->pdo->prepare("INSERT INTO carsales (car_brand, car_model, car_color, car_price, client_name, client_phone) VALUES (:brand, :model, :color, :price, :client_name, :client_phone)");
        $stmt->execute([
            ':brand' => $car_brand,
            ':model' => $car_model,
            ':color' => $car_color,
            ':price' => $car_price,
            ':client_name' => $client_name,
            ':client_phone' => $client_phone
        ]);
    }

    public function updateStock($car_brand, $car_model, $car_color, $car_price) {
        $stmt = $this->pdo->prepare("UPDATE cars_inventory SET stock_quantity = stock_quantity - 1 WHERE brand = :brand AND model = :model AND color = :color AND price = :price");
        $stmt->execute([
            ':brand' => $car_brand,
            ':model' => $car_model,
            ':color' => $car_color,
            ':price' => $car_price
        ]);
    }
}