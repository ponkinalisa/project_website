<?php
require_once 'config.php';

class DB {
    private $pdo;

    public function __construct($pdo) {
        if (!$pdo instanceof PDO) {
            throw new InvalidArgumentException('Неверный тип аргумента $pdo. Ожидается экземпляр PDO.');
        }
        $this->pdo = $pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        
        // Проверяем выполнение prepare()
        if ($stmt === false) {
            throw new Exception('Ошибка подготовки запроса: ' . var_export($this->pdo->errorInfo(), true));
        }

        // Выполняем запрос с параметрами
        $executionResult = $stmt->execute($params);

        // Проверка успешного выполнения execute()
        if ($executionResult === false) {
            throw new Exception('Ошибка выполнения запроса: ' . var_export($stmt->errorInfo(), true));
        }

        return $stmt;
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }
}

try {
    // Создаем объект для работы с базой
    $db = new DB($pdo);
} catch (Exception $e) {
    die('Ошибка инициализации базы данных: ' . $e->getMessage());
}
?>
