<?php
require_once 'config.php';
include('user.php');

session_start(); // Начнем сессию для работы с CSRF-токеном

if (isset($_SESSION['user_id'])) {
    die('Вы уже в аккаунте!');
}

// Проверяем, был ли отправлен POST-запрос с данными для регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Проверка правильности введённых данных
    $username = trim($_POST['username']); // Удаляем лишние пробелы
    $tel = $_POST['tel'];
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); // Очищаем e-mail

    $user = new User($db);
    $reg_user = $user->register($username, $email, $tel);
    $_SESSION['user_id'] = $user->getUserId($email)['id'];

    echo "Пользователь успешно зарегистрирован.";
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
<form method="post" action='register_user.php'>
    Логин: <input type="text" name="username" required><br>
    Номер телефона: <input type="tel" name="tel" id="tel" pattern="[0-9]{11}" placeholder="Телефон" required><br>
    E-mail: <input type="email" name="email" required><br>
    <input type="submit" value="Зарегистрировать">
    <a href='login_user.php'>Войти</a>
</form>
</body>
