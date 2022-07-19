<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('../librares/connect.php');
$data = [];
if(isset($_GET['cites'])){
    $data = getData('SELECT * FROM `order_cites`');
}
if(isset($_GET['status'])){
    session_start();
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
    $data = getData($query);
}
if(isset($_GET['toc'])){
    $data = getData('SELECT * FROM `order_connection`
    ');
}
echo json_encode($data, JSON_UNESCAPED_UNICODE);