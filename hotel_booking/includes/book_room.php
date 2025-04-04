<?php 
session_start();
include('db.php');
include('user.php');
include('booking.php');
include('room.php');

if (!empty($_POST)){
    if (!isset($_SESSION['user_id'])) {
        die('Сначала войдите в аккаунт или зарегистрируйтесь!');
    }
    $name = $_POST['name'];
    $name_pet =  $_POST['name_pet'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
    $room_name = $_POST['name_room'];
    try{
        $user = new User($db);
    $id = $user->getUserId($email)['id'];

    $room = new Room($db);
    $room_id = $room->getRoomByName($room_name)['id'];

    $book = new Booking($db);
    $book->createBooking($id, $room_id, $date1, $date2);
    echo 'Добавлена заявка';

    header("Location: ../../index.html");
    exit;
    }catch (PDOException $e) {
        echo 'Возникла неожиданная';
    }
}
?>