<?php
namespace App\Models;

use PDO;

class UserModel {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function register(string $username, string $email, string $password, string $phone): bool {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
        $stmt = $this->pdo->prepare("INSERT INTO userinfo (username, email, password, phone, role) VALUES (:username, :email, :password, :phone, 'User')");
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':phone' => $phone
        ]);
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM userinfo WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByUsername(string $username): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM userinfo WHERE username = :username");
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getUserRole(string $username): ?string {
        $stmt = $this->pdo->prepare("SELECT role FROM userinfo WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['role'] ?? null;
    }

    public function login(string $email, string $password): ?array {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}