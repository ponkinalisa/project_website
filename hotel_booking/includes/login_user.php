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
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); // Очищаем e-mail

    $user = new User($db);
    $_SESSION['user_id'] = $user->getUserId($email)['id'];

    echo "Вход успешен.";
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
    Почта: <input type="email" name="email" required><br>
    <input type="submit" value="Войти">
    <a href='register_user.php'>Зарегистрироваться</a>
</form>
</body>
