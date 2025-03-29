<?php
// Настройки подключения к базе данных
$host = 'localhost';
$dbname = 'hotel_booking';
$username = 'root';  // Логин для MySQL
$password = 'root';  // Пароль, если есть

try {
    // Создание подключения к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Пример использования prepared statement
    $stmt = $pdo->prepare('SELECT * FROM users WHERE name = :username');
    $stmt->execute(['username' => 'admin']);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Ошибка подключения: ' . $e->getCode() . ' - ' . $e->getMessage();
}
?>
