<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../librares/connect.php');
session_start();
if (isset($_SESSION['user'])) {
    $query = "SELECT id, name FROM order_status WHERE name NOT IN (";
    if (!in_array(7, $_SESSION['user']['rights'])) {
        $query .= "'Подготовка заказа'";
        if (!in_array(8, $_SESSION['user']['rights'])) {
            $query .= ",'Доставка'";
            if (!in_array(9, $_SESSION['user']['rights'])) {
                $query .= ",'Завершённый', 'Завершенный без оплаты')";
            } else {
                $query .= ")";
            }
        } else {
            $query .= ")";
        }
    } else if (!in_array(8, $_SESSION['user']['rights'])) {
        $query .= "'Доставка'";
        if (!in_array(9, $_SESSION['user']['rights'])) {
            $query .= ",'Завершённый', 'Завершенный без оплаты')";
        } else {
            $query .= "))";
        }
    } else if (!in_array(9, $_SESSION['user']['rights'])) {
        $query .= "'Завершённый', 'Завершенный без оплаты')";
    } else {
        $query = "SELECT id, name FROM order_status";
    }
    $data[] =
        [
            "openList" => true,
            'value' => "status",
            "name" => "Статус",
            "list" => $connect->getData($query),
        ];
    if (in_array(11, $_SESSION['user']['rights'])) {
        if (in_array(12, $_SESSION['user']['rights'])) {
            $data[] = [
                "openList" => false,
                'value' => "select_date",
                "name" => "Назначено?",
                "list" => [
                    ['id' => 0, 'name' => "Назначеные замеры"],
                    ['id' => 1, 'name' => "Не назначеные замеры"],
                    ['id' => 2, 'name' => "Назначеные доставки"],
                    ['id' => 3, 'name' => "Не назначеные доставки"],
                ],
            ];
        } else {
            $data[] = [
                "openList" => false,
                'value' => "select_date",
                "name" => "Назначено?",
                "list" => [
                    ['id' => 0, 'name' => "Назначеные замеры"],
                    ['id' => 1, 'name' => "Не назначеные замеры"],
                ],
            ];
        }
    } else if (in_array(12, $_SESSION['user']['rights'])) {
        $data[] = [
            "openList" => false,
            'value' => "select_date",
            "name" => "Назначено?",
            "list" => [
                ['id' => 2, 'name' => "Назначеные доставки"],
                ['id' => 3, 'name' => "Не назначеные доставки"],
            ],
        ];
    }
} else {
    $query = "SELECT id, name FROM order_status";
    $data[] =
        [
            "openList" => true,
            'value' => "status",
            "name" => "Статус",
            "list" => $connect->getData($query),
        ];
    $data[] = [
        "openList" => false,
        'value' => "select_date",
        "name" => "Назначено?",
        "list" => [
            ['id' => 0, 'name' => "Назначеные замеры"],
            ['id' => 1, 'name' => "Не назначеные замеры"],
            ['id' => 2, 'name' => "Назначеные доставки"],
            ['id' => 3, 'name' => "Не назначеные доставки"],
        ],
    ];
}

$data[] = [
    "openList" => false,
    'value' => "city",
    "name" => "Город",
    "list" => $connect->getData("SELECT id,name FROM order_cites"),
];
$data[] = [
    "openList" => false,
    'value' => "connection",
    "name" => "Тип связи",
    "list" => $connect->getData("SELECT id,name FROM order_connection"),
];


echo json_encode($data, JSON_UNESCAPED_UNICODE);
