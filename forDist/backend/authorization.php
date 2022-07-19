<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
session_start();
include_once('./librares/connect.php');

$result = query("SELECT `id`, `name`,`password` FROM `users` WHERE `login`='" . $_POST['login'] . "'", "result");
if ($result->num_rows === 1) {

    $data = $result->fetch_assoc();
    if (password_verify($_POST['password'], $data['password'])) {
        $data['admin'] = 0;
        $right = getData("SELECT `id_right` as `right`, id_category FROM `rights_users`,`rights` WHERE `rights`.`id` = `id_right` AND `id_user` = " . $data['id'] . ";");
        foreach ($right as $elem) {
            if ($elem['id_category'] == 1) {
                $data['admin'] = 1;
            }
            $data['rights'][] = $elem['right'];
            $_SESSION['user'] = $data;
        }
    } else {
        $data = ["error" => "Неверный логин или пароль"];
    }
} else {
    $data = ["error" => "Неверный логин или пароль"];
}

// arrayLog($_SESSION);
echo json_encode($_SESSION['user'], JSON_UNESCAPED_UNICODE);
