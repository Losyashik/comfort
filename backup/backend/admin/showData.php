<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../../backend/librares/connect.php');

foreach ($_POST['item'] as $item) {
    $connect->query("UPDATE `linoleum` SET `show_product` = " . $_POST['show'] . " WHERE id = " . $item);
}
$data['text'] = (bool)$_POST['show'] ? "Показано" : "Скрыто";
$data['error'] = false;
$data['active'] = true;
echo  json_encode($data, JSON_UNESCAPED_UNICODE);
