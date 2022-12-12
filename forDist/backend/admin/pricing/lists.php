<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once("../../librares/connect.php");
// print_r($_POST);
$data = [
    "linoleum" => [
        "makers" => $connect->getData("SELECT `id`, `name` FROM linoleum_maker"),
        "collections" => $connect->getData("SELECT `id`, `name` FROM linoleum_collection"),
        "linoleums" => $connect->getData("SELECT `id`, `name` FROM linoleum"),

    ],
    "plinth" => [
        "makers" => $connect->getData("SELECT `id`, `name` FROM plinth_maker"),
        "collections" => $connect->getData("SELECT `id`, `name` FROM plinth_collection"),
        "plinths" => $connect->getData("SELECT `id`, `name` FROM plinth"),
    ],
    "doorstep" => [
        "makers" => $connect->getData("SELECT `id`, `name` FROM doorstep_maker"),
        "doorsteps" => $connect->getData("SELECT doorstep.id as id, concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color ORDER BY doorstep_color.name ASC"),
    ],
    "related" => [
        "relateds" => $connect->getData("SELECT `id`, `name` FROM related ORDER BY id ASC"),
    ],
    "providers" => $connect->getData("SELECT `id`, `name` FROM provider"),
];

echo  json_encode($data, JSON_UNESCAPED_UNICODE);
