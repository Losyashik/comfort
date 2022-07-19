<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
if (isset($_GET['id'])) {
    include_once("./../librares/connect.php");

    $id = $_GET['id'];
    $data = getData("SELECT * FROM orders WHERE id = $id")[0];
    if ($data['id_city'] != null) {
        $city = getData('Select name from order_cites Where id = ' . $data['id_city'])[0]['name'];
    } else {
        $city = "";
    }
    $dataPage = [
        'nick' => $data['nick'],
        'initials' => $data['full_name'],
        'status' => getData("select name from order_status where id=" . $data['id_status'])[0]['name'],
        'number' => $data['number'],
        'toc' => getData('Select name from order_connection Where id = ' . $data['id_connection'])[0]['name'],
        'note' => $data['note'],
        'no' => $data['id'],
        'city' => $city,
        'addres' => $data['adres'],
        'sum' => $data['sum'],
        'weight' => $data['weight'],
        'lastKey' => getData("SELECT count(id) FROM product_list WHERE id_order = " . $data['id'])[0]['count(id)'],
    ];
    ini_set('display_errors', 'off');
    include_once('./../librares/connect.php');
    $sql = "SELECT * FROM product_list WHERE id_order = $id Order by id_accessories,id_plinth,id_linoleum DESC";
    $data = getData($sql);
    $dataPage['productList']['linoleum'] = [];
    $dataPage['productList']['plinth'] = [];
    $dataPage['productList']['accessories'] = [];
    $sum = 0;
    $i = 1;
    foreach ($data as $elem) {
        $list = [];
        if ($elem['id_linoleum'] != null) {
            $list['key'] = $i++;
            $list['id'] = $elem['id'];
            $list['id_linoleum'] = $elem['id_linoleum'];

            $list['product'] = " " . getData("SELECT `name` FROM linoleum_maker WHERE id = (SELECT id_maker FROM linoleum WHERE id = " . $elem['id_linoleum'] . ")")[0]['name'] . " " . getData("SELECT `name` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $elem['id_linoleum'] . ")")[0]['name'] . " " . getData("SELECT name FROM linoleum WHERE id = " . $elem['id_linoleum'])[0]['name'];

            $list['ei'] = "м<sup>2</sup>";

            $list["weight"] = getData("SELECT `weight` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $elem['id_linoleum'] . ")")[0]['weight'];
            $list["width"] = [
                "select" => getData("SELECT width FROM linoleum_width WHERE id = " . $elem['id_width'])[0]['width'],
            ];
            foreach (getData("SELECT width FROM linoleum_width WHERE id_linoleum = " . $elem['id_linoleum']) as $width) {
                $list["width"]['all'][] = $width['width'];
            }
            $list["len"] = $elem["length"];
            $list["price"] = [
                "fact" => $elem['final_price']
            ];
            $sum += $list["width"]['select'] * $list["len"];
            $collection = getData("SELECT id_collection FROM linoleum WHERE id = " . $elem['id_linoleum'])[0]['id_collection'];
            $data = getData("SELECT price FROM linoleum_width WHERE id_linoleum = " . $elem['id_linoleum']);
            $sum_price = 0;
            foreach ($data as $el) {
                $sum_price += $el['price'];
            }
            $num = query("SELECT price FROM linoleum_width WHERE id_linoleum = " . $elem['id_linoleum'], "result")->num_rows;
            if ($num != 0) {
                $avg = $sum_price / $num;
            } else {
                $num = 0;
            }
            $data = 0;
            $data = getData("SELECT * FROM `linoleum_allowances` WHERE id_collection = $collection")[0];
            $list["price"]['rrc']['all'] = [
                'p0_10' => round($avg + ($avg * ($data['0_10'] / 100)), 2),
                'p11_15' => round($avg + ($avg * ($data['11_15'] / 100)), 2),
                'p16_20' => round($avg + ($avg * ($data['16_20'] / 100)), 2),
                'p21_30' => round($avg + ($avg * ($data['21_30'] / 100)), 2),
                'p31_40' => round($avg + ($avg * ($data['31_40'] / 100)), 2),
                'p41_55' => round($avg + ($avg * ($data['41_55'] / 100)), 2),
                'p56_70' => round($avg + ($avg * ($data['56_70'] / 100)), 2),
            ];
            $dataPage['productList']['linoleum'][] = $list;
        }
        if ($elem['id_plinth'] != NULL) {
            $list['key'] = $i++;
            $list['id'] = $elem['id'];
            $list['id_plinth'] = $elem['id_plinth'];
            $list['product'] =  "Плинтуса " . getData("SELECT name FROM plinth WHERE id = " . $elem['id_plinth'])[0]['name'];
            $list['ei'] = "шт";
            $list['col_vo'] = $elem['col-vo'];
            $list['price'] = getData("SELECT price FROM plinth_collection,plinth WHERE id_collection = plinth_collection.id and plinth.id = " . $elem['id_plinth'])[0]['price'];
            $list['weight'] = getData("SELECT weight FROM plinth_collection,plinth WHERE id_collection = plinth_collection.id and plinth.id = " . $elem['id_plinth'])[0]['price'];
            $list['final_price'] = $elem['final_price'];
            $dataPage['productList']['plinth'][] = $list;
        }
        if ($elem['id_accessories'] != NULL) {
            $list['key'] = $i++;
            $list['id'] = $elem['id'];
            $list['id_accessories'] = $elem['id_accessories'];
            $list['product'] =  getData("SELECT name FROM plinth_accessories WHERE id = " . $elem['id_accessories'])[0]['name'];
            $list['ei'] = "шт";
            $list['col_vo'] = $elem['col-vo'];
            $list['price'] = getData("SELECT price FROM plinth_accessories WHERE  id = " . $elem['id_accessories'])[0]['price'];
            $list['final_price'] = $elem['final_price'];
            $dataPage['productList']['accessories'][] = $list;
        }
    }
    if ($sum > 0 && $sum <= 10) $rKey = "p0_10";
    else if ($sum <= 15) $rKey = "p11_15";
    else if ($sum <= 20) $rKey = "p16_20";
    else if ($sum <= 30) $rKey = "p21_30";
    else if ($sum <= 40) $rKey = "p31_40";
    else if ($sum <= 55) $rKey = "p41_55";
    else $rKey = "p56_70";
    foreach ($dataPage['productList']['linoleum'] as $key => $elem) {
        $dataPage['productList']['linoleum'][$key]["price"]['rrc']['selected'] =  $productList['linoleum'][$key]["price"]['rrc']['all'][$rKey];
    }
    echo json_encode($dataPage, JSON_UNESCAPED_UNICODE);
}
