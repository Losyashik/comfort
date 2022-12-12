<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once("../../librares/connect.php");
// print_r($_POST);
$data['sort'] = ["maker" => $connect->getData("SELECT id,name FROM linoleum_maker")];
if (isset($_POST['maker'])) {
    if ($_POST['maker'] != 0) {
        $data['list'] = $connect->getData("SELECT id,name FROM linoleum_maker WHERE id_maker = " . $_POST['maker']);
    } else {
        $data['list'] = $connect->getData("SELECT id,name FROM linoleum_collection");
    }
} else {
    $data['list'] = $connect->getData("SELECT id,name FROM linoleum_collection");
}

echo  json_encode($data, JSON_UNESCAPED_UNICODE);
