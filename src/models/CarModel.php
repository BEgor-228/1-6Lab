<?php
namespace App\Models;

use PDO;

class CarModel {
    private $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function getAllCars(): array {
        $stmt = $this->pdo->query("SELECT brand, model, color, price, stock_quantity FROM cars_inventory ORDER BY brand, model, color");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}