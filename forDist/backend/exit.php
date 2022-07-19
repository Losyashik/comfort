<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");

session_start();
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}
session_destroy();


