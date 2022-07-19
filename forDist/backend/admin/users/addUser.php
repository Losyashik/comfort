<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");

include_once('./../../../backend/librares/connect.php');

if (query("SELECT * FROM users WHERE login = '" . $_POST['login'] . "'", "result")->num_rows == 0) {
    if (query("SELECT * FROM users WHERE name = '" . $_POST['name'] . "'", "result")->num_rows == 0) {
        $login = $_POST['login'];
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], 0);
        query("INSERT INTO users (name,login,password) VALUES ('$name','$login','$password')");
        $data['text'] = "Пользователь зарегистрирован";
        $data['error'] = false;
        $data['active'] = true;
    } else {
        $data['text'] = "Пользователь с таким именем был зарегестрирован раньше";
        $data['error'] = true;
    }
} else {
    $data['text'] = "Пользователь с таким логином был зарегестрирован раньше";
    $data['error'] = true;
}
echo  json_encode($data, JSON_UNESCAPED_UNICODE);
