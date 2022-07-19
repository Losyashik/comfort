<?php
// header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");

include_once("../librares/connect.php");

if (isset($_POST['list']) and count($_POST['list']) != 0) {
    if ($_POST['type'] == "measuring") {
        foreach ($_POST['list'] as $elem) {
            query("UPDATE `orders` SET `measuring_time`='" . $elem['time'] . "'  WHERE id = " . $elem['id']);
        }
    } else if ($_POST['type'] == "delivery") {
        foreach ($_POST['list'] as $elem) {
            query("UPDATE `orders` SET `delivery_time`='" . $elem['time'] . "' WHERE id = " . $elem['id']);
        }
    }
    $data['text'] = "Изменения сохранены";
    $data['active'] = true;
    echo  json_encode($data, JSON_UNESCAPED_UNICODE);
}
