<?php
require_once 'config.php';

session_start(); // Начнем сессию для работы с CSRF-токеном

// Генерируем уникальный токен для защиты от CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Генерация токена
}

// Проверяем, был ли отправлен POST-запрос с данными для регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка наличия токена CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid token!');
    }

    // Проверка правильности введённых данных
    $username = trim($_POST['username']); // Удаляем лишние пробелы
    $password = $_POST['password'];
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); // Очищаем e-mail

    // Валидация данных
    if (empty($username) || empty($password) || empty($email)) {
        die('Заполните все поля!');
    }

    // Проверка длины пароля
    if (strlen($password) < 6) {
        die('Пароль должен содержать минимум 6 символов!');
    }

    // Проверка корректности e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die('Некорректный e-mail!');
    }

    // Хешируем пароль
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Проверка уникальности логина и e-mail
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = :username OR email = :email");
    $stmt->execute(['username' => $username, 'email' => $email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        die('Логин или e-mail уже используется!');
    }

    // Сохраняем данные в базе данных
    $sql = "INSERT INTO admins (username, password, email) VALUES (:username, :password, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username, 'password' => $hashedPassword, 'email' => $email]);

    echo "Администратор успешно зарегистрирован.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>регистрация админов</title>
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
<div class="logo">
    <a class="logo" href="index.html" title="Главная страница"><img src="../../img/logo.svg" alt="Логотип" width="63" height="52"
                                                                                title="Логотип отеля Котейка"></a>
            </div>
<form method="post">
    Логин: <input type="text" name="username" required><br>
    Пароль: <input type="password" name="password" required><br>
    E-mail: <input type="email" name="email" required><br>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Токен CSRF -->
    <input type="submit" value="Зарегистрировать">
    <a href='login.php'>Войти</a>
</form>
</body>
