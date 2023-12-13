<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../../../backend/librares/connect.php');

echo '[{"id":"1","addres":"Шкенин Антон Александрович","margin":"80","action":"Замер","date":"2022-09-05"}]';
