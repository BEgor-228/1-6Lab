<?php
namespace App\Models;

use PDO;

class AppointmentModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function getAllSales(): array {
        $stmt = $this->pdo->query("SELECT car_brand, car_model, car_price, car_color, client_name, client_phone FROM carsales");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}