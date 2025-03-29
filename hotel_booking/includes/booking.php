<?php
require_once 'db.php';

class Booking {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Создание нового бронирования
    public function createBooking($userId, $roomId, $checkIn, $checkOut, $status = 'pending') {
        $sql = "INSERT INTO bookings (user_id, room_id, check_in, check_out, status) VALUES (:user_id, :room_id, :check_in, :check_out, :status)";
        return $this->db->query($sql, [
            'user_id' => $userId,
            'room_id' => $roomId,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'status' => $status
        ]);
    }

    // Получение всех бронирований для пользователя
    public function getBookingsByUser($userId) {
        $sql = "SELECT * FROM bookings WHERE user_id = :user_id";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
}
?>
