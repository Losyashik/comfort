<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once("../../librares/connect.php");
// print_r($_POST);
$data = [
    "linoleum" => [
        "makers" => getData("SELECT `id`, `name` FROM linoleum_maker"),
        "collections" => getData("SELECT `id`, `name` FROM linoleum_collection"),
        "linoleums" => getData("SELECT `id`, `name` FROM linoleum"),

    ],
    "plinth" => [
        "makers" => getData("SELECT `id`, `name` FROM plinth_maker"),
        "collections" => getData("SELECT `id`, `name` FROM plinth_collection"),
        "plinths" => getData("SELECT `id`, `name` FROM plinth"),
    ],
    "doorstep" => [
        "makers" => getData("SELECT `id`, `name` FROM doorstep_maker"),
        "doorsteps" => getData("SELECT doorstep.id as id, concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color"),
    ],
    "providers" => getData("SELECT `id`, `name` FROM provider"),
];

echo  json_encode($data, JSON_UNESCAPED_UNICODE);
