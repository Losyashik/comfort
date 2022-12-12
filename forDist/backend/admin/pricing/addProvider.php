<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once("../../librares/connect.php");
if (isset($_POST['add'])) {

    $link = $connect->query("INSERT INTO `provider`(`name`,`short_name`) VALUES ('" . $_POST['name'] . "','" . $_POST['short_name'] . "')");

    $insert_id = $link->insert_id;
    foreach ($_POST['contacts'] as $elem) {
        $connect->query("INSERT INTO `provider_address`(`address`,`number`,`id_provider`) VALUES ('" . $elem['address'] . "','" . $elem['number'] . "',$insert_id)");
    }
}
$data = $connect->getData("SELECT id,name FROM provider");
echo  json_encode($data, JSON_UNESCAPED_UNICODE);
