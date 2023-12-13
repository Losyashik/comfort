<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
session_start();
include_once('./connect.php');
$data = [];

$data['cites'] = $connect->getData('SELECT * FROM `order_cites`');
$data['payment'] = $connect->getData("SELECT id,name,short_name FROM order_payment_method");


if (isset($_SESSION['user'])) {
    $query = "SELECT id, name FROM order_status WHERE name NOT IN (";
    if (!in_array(7, $_SESSION['user']['rights'])) {
        $query .= "'Подготовка заказа'";
        if (!in_array(8, $_SESSION['user']['rights'])) {
            $query .= ",'Доставка'";
            if (!in_array(9, $_SESSION['user']['rights'])) {
                $query .= ",'Завершённый')";
            } else {
                $query .= ")";
            }
        } else {
            $query .= ")";
        }
    } else if (!in_array(8, $_SESSION['user']['rights'])) {
        $query .= "'Доставка'";
        if (!in_array(9, $_SESSION['user']['rights'])) {
            $query .= ",'Завершённый')";
        } else {
            $query .= "))";
        }
    } else if (!in_array(9, $_SESSION['user']['rights'])) {
        $query .= "'Завершённый')";
    } else {
        $query = "SELECT id, name FROM order_status";
    }
} else {
    $query = "SELECT id, name FROM order_status";
}

$data['status'] = $connect->getData($query);
$data['toc'] = $connect->getData('SELECT * FROM `order_connection`');

echo json_encode($data, JSON_UNESCAPED_UNICODE);
