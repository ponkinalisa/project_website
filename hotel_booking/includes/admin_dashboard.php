<?php
session_start();
require_once 'config.php';

// Проверяем, что администратор вошел в систему
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Здесь код панели управления
echo "<p style='left: 0;'>Добро пожаловать, {$_SESSION['username']}!<br><p>";
echo '<a href="logout.php">Выйти</a><br><br>';

// Проверяем, что администратор вошел в систему
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Получаем все заявки
$sql = "SELECT * FROM bookings";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h2>Список заявок на бронирование</h2>";
echo "<table>";
echo "<tr><th>ID</th><th>Номер</th><th>Дата заезда</th><th>Дата выезда</th><th>Статус</th><th>Действия</th></tr>";
foreach ($bookings as $booking) {
    // Экранируем данные для безопасного вывода в HTML
    $id = htmlspecialchars($booking['id']);
    $room_id = htmlspecialchars($booking['room_id']);
    $check_in = htmlspecialchars($booking['check_in']);
    $check_out = htmlspecialchars($booking['check_out']);
    $status = htmlspecialchars($booking['status']);
    if ($status == 'confirmed' or $status == 'canceled'){
        continue;
    }
    echo "<tr>
            <td>$id</td>
            <td>$room_id</td>
            <td>$check_in</td>
            <td>$check_out</td>
            <td>$status</td>
            <td>
                <a href='approve_booking.php?id=$id' style='color: black'>Одобрить</a> | 
                <a href='cancel_booking.php?id=$id' style='color: black'>Отменить</a>
            </td>
        </tr>";
}
echo "</table>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>работа админов</title>
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
    <br><br><br>
<h3>Отменить заявку</h3>
<form method="get" action='cancel_booking.php'>
    ID заявки: <input type="number" name="id" required><br>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Токен CSRF -->
    <input type="submit" value="Отправить">
</form>
<br>
<h3>Одобрить заявку</h3>
<form method="get" action='approve_booking.php'>
    ID заявки: <input type="number" name="id" required><br>
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>"> <!-- Токен CSRF -->
    <input type="submit" value="Отправить">
</form>
</body>
</html>