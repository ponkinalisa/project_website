<?php
require_once 'db.php';

class Review {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Добавление отзыва
    public function addReview($userId, $roomId, $text, $rating) {
        // Проверка типов данных
        if (!is_int($userId) || !is_int($roomId) || empty($text) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
            throw new InvalidArgumentException('Некорректные данные для добавления отзыва.');
        }

        $sql = "INSERT INTO reviews (user_id, room_id, text, rating) VALUES (:user_id, :room_id, :text, :rating)";

        try {
            return $this->db->query($sql, [
                'user_id' => $userId,
                'room_id' => $roomId,
                'text' => $text,
                'rating' => $rating
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Ошибка при добавлении отзыва.', 0, $e);
        }
    }

    // Получение отзывов для конкретного номера
    public function getReviewsByRoom($roomId) {
        // Проверка типов данных
        if (!is_int($roomId)) {
            throw new InvalidArgumentException('Некорректный идентификатор номера.');
        }

        $sql = "SELECT * FROM reviews WHERE room_id = :room_id";

        try {
            return $this->db->fetchAll($sql, ['room_id' => $roomId]);
        } catch (PDOException $e) {
            throw new RuntimeException('Ошибка при получении отзывов.', 0, $e);
        }
    }
}
?>
