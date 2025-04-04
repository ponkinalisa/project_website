<?php
session_start();
require_once 'config.php';

// Проверяем, что администратор вошел в систему
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Получаем ID заявки и проверяем его на корректность
$bookingId = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;

if (!$bookingId) {
    echo "Неверный ID заявки.";
    header("Location: admin_dashboard.php");
    exit;
}

// Обновляем статус заявки на "canceled"
$sql = "UPDATE bookings SET status = 'canceled' WHERE id = :id";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute(['id' => $bookingId]);
    echo "Заявка успешно отменена.";
    header("Location: admin_dashboard.php");
    exit;
} catch (PDOException $e) {
    echo "Ошибка отмены заявки: " . $e->getMessage();
    header("Location: admin_dashboard.php");
    exit;
}
?>
