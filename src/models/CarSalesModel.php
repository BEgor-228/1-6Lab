<?php
namespace App\Models;

use PDO;

class CarSalesModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getOrdersByPhone(string $phone): array {
        $stmt = $this->pdo->prepare("SELECT car_brand, car_model, car_price, car_color, client_name, client_phone FROM carsales WHERE client_phone = :phone");
        $stmt->execute([':phone' => $phone]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
