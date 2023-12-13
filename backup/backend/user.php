<?php
header("Content-Type: aplication/json");
// header("Access-Control-Allow-Origin:*");
// header("Access-Control-Allow-Credentials:true");
include_once "./controlers/userControler.php";
$data = json_decode(file_get_contents('php://input'), JSON_UNESCAPED_UNICODE) ?? $_POST;

if (isset($data['userAuthorizationData'])) {
    $user->authorization($data['userAuthorizationData']);
}
if (isset($data["tabs"])) {
    // $user->updateTabs($data['tabs']);
    http_response_code(201);
}
if (isset($_GET["exit"])) {
    $user->exit();
    http_response_code(201);
}
