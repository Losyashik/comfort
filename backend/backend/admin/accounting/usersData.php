<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../../../backend/librares/connect.php');


echo '[{"id":"1","name":"Шкенин Антон Александрович"},{"id":"2","name":"admin"},{"id":"3","name":"Шкенин Павел Александрович"}]';
