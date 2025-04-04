<?php
require_once 'db.php';

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Регистрация нового пользователя
    public function register($name, $email, $phone) {
        // Проверка входных данных
        if (empty($name) || empty($email) || empty($phone)) {
            throw new InvalidArgumentException('Все поля обязательны для заполнения.');
        }

        // Проверка формата электронной почты
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Некорректный формат электронной почты.');
        }

        // Проверка формата телефонного номера
        if (!preg_match('/^[0-9]{10,15}$/', $phone)) {
            throw new InvalidArgumentException('Некорректный формат телефонного номера.');
        }

        $sql = "INSERT INTO users (name, email, phone) VALUES (:name, :email, :phone)";

        try {
            return $this->db->query($sql, [
                'name' => htmlspecialchars($name),
                'email' => htmlspecialchars($email),
                'phone' => htmlspecialchars($phone)
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Уникальность нарушения
                throw new RuntimeException('Пользователь с таким email уже существует.', 0, $e);
            } else {
                throw new RuntimeException('Ошибка регистрации пользователя.', 0, $e);
            }
        }
    }

    // Проверка существования пользователя по email
    public function getUserByEmail($email) {
        if (empty($email)) {
            throw new InvalidArgumentException('Электронная почта не указана.');
        }

        $sql = "SELECT * FROM users WHERE email = :email";

        try {
            return $this->db->fetchOne($sql, ['email' => htmlspecialchars($email)]);
        } catch (PDOException $e) {
            throw new RuntimeException('Ошибка получения пользователя.', 0, $e);
        }
    }
    public function getUserId($email){
        if (empty($email)) {
            throw new InvalidArgumentException('Электронная почта не указана.');
        }

        $sql = "SELECT id FROM users WHERE email = :email";

        try {
            return $this->db->fetchOne($sql, ['email' => htmlspecialchars($email)]);
        } catch (PDOException $e) {
            throw new RuntimeException('Ошибка получения пользователя.', 0, $e);
        }
    }
}

?>
