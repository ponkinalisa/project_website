<?php
session_start();
require_once 'config.php';

// Генерируем уникальный токен для защиты от CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Генерация токена
}

// Проверяем, был ли отправлен POST-запрос с данными для входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка наличия токена CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid token!');
    }

    // Проверка правильности введённых данных
    $username = trim($_POST['username']); // Удаляем лишние пробелы
    $password = $_POST['password'];

    // Валидация данных
    if (empty($username) || empty($password)) {
        die('Заполните все поля!');
    }

    // Получаем администратора из базы данных
    $sql = "SELECT * FROM admins WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        // Пароль верен, создаем сессию
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        header("Location: admin_dashboard.php"); // Перенаправляем в панель управления
        exit;
    } else {
        echo "Неверный логин или пароль.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>вход админов</title>
<meta name='viewport' content='width=device-width,initial-scale=1'/>
    <meta content='true' name='HandheldFriendly'/>
    <meta content='width' name='MobileOptimized'/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta content='yes' name='apple-mobile-web-app-capable'/>
    <link rel="icon" href="../../img/favicon-32x32.png" type="image/png">
    <link rel="stylesheet" href="../../css/rooms.css" type="text/css">
    <link rel="stylesheet" href="../../css/general.css" type="text/css">
<link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
<form method="post">
    Логин: <input type="text" name="username" required><br>
    Пароль: <input type="password" name="password" required><br>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Токен CSRF -->
    <input type="submit" value="Войти">
    <a href='register.php'>Зарегистрироваться</a>
</form>
</body>
