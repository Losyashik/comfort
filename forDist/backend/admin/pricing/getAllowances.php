<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once("../../librares/connect.php");
foreach (getData("SELECT * FROM linoleum_allowances WHERE id_collection = " . $_POST['item'])[0] as $key => $item) {
    if ($key != "id_collection") $data["p$key"] = $item;
    else $data[$key] = $item;
}
echo  json_encode($data, JSON_UNESCAPED_UNICODE);
