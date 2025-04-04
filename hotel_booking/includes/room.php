<?php
require_once 'db.php';

class Room {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Получение списка номеров с фильтрацией и сортировкой
    public function getRooms($filters = [], $sortBy = 'price', $order = 'ASC') {
        // Валидируем порядок сортировки
        $allowedOrders = ['ASC', 'DESC'];
        if (!in_array(strtoupper($order), $allowedOrders)) {
            throw new InvalidArgumentException("Недопустимый порядок сортировки: {$order}");
        }

        // Валидируем столбец для сортировки
        $allowedSortColumns = ['price', 'area']; // Дополните список разрешенных столбцов
        if (!in_array($sortBy, $allowedSortColumns)) {
            throw new InvalidArgumentException("Недопустимый столбец для сортировки: {$sortBy}");
        }

        $sql = "SELECT * FROM rooms WHERE available = 1";
        
        // Подготавливаем параметры для фильтров
        $whereParams = [];
        if (!empty($filters)) {
            foreach ($filters as $column => $value) {
                $paramName = ":{$column}";
                $sql .= " OR {$column} = {$paramName}";
                $whereParams[$paramName] = $value;
            }
        }

        $sql .= " ORDER BY {$sortBy} {$order}";
        
        // Объединяем параметры для фильтра и соритровки
        $params = array_merge($whereParams, []);

        return $this->db->fetchAll($sql, $params);
    }

    // Получение информации о номере
    public function getRoomById($id) {
        $sql = "SELECT * FROM rooms WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    // Добавление нового номера
    public function addRoom($data) {
        $sql = "INSERT INTO rooms (name, description, price, image_url, learn_more_url, area) VALUES (:name, :description, :price, :image_url, :earn_more_url, :area)";
        return $this->db->query($sql, $data);
    }

    // Получение информации о номере
    public function getRoomByName($name) {
        $sql = "SELECT * FROM rooms WHERE LOWER(name) = LOWER(:name)";
        return $this->db->fetchOne($sql, ['name' => $name]);
    }
}
?>
