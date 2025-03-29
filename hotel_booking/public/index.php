<?php
require_once '../includes/room.php';

// Обработка GET-запроса на получение номеров
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'getRooms') {
        $filters = [];

        // Валидация цены
        if (isset($_GET['price']) && is_numeric($_GET['price'])) {
            $filters['price'] = $_GET['price'];
        }

        // Валидация параметров сортировки
        $allowedSortFields = ['price', 'name']; // Допустимые поля для сортировки
        $sortBy = isset($_GET['sortBy']) && in_array($_GET['sortBy'], $allowedSortFields) ? $_GET['sortBy'] : 'price';

        $allowedOrderValues = ['ASC', 'DESC']; // Допустимые значения для порядка сортировки
        $order = isset($_GET['order']) && in_array(strtoupper($_GET['order']), $allowedOrderValues) ? strtoupper($_GET['order']) : 'ASC';

        // Инициализируем объект комнаты
        $room = new Room($db);

        try {
            // Получаем список комнат с учетом фильтров и сортировок
            $rooms = $room->getRooms($filters, $sortBy, $order);

            // Возвращаем JSON с результатами
            header('Content-Type: application/json');
            
            echo json_encode($rooms, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Произошла ошибка при обработке запроса.']);
        }
    }
}
?>
