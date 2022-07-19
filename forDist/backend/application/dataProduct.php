<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('../librares/connect.php');
$list = [];
if (isset($_GET['id_linoleum'])) {
    $list['key'] = $_GET['key'] + 1;
    $list['id_linoleum'] = $_GET['id_linoleum'];
    $list['product'] = getData("SELECT `name` FROM linoleum_maker WHERE id = (SELECT id_maker FROM linoleum WHERE id = " . $list['id_linoleum'] . ")")[0]['name'] . " " . getData("SELECT `name` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $list['id_linoleum'] . ")")[0]['name'] . " " . getData("SELECT name FROM linoleum WHERE id = " . $_GET['id_linoleum'])[0]['name'];
    $list['ei'] = "м<sup>2</sup>";
    $list['width']['all'] = [];
    foreach (getData("SELECT width FROM linoleum_width WHERE id_linoleum = " . $_GET['id_linoleum']) as $width) {
        $list["width"]['all'][] = $width['width'];
    }
    $list["width"]["select"] = $list["width"]['all'][0];
    $list["len"] = 0;
    $list["weight"] = getData("SELECT `weight` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $list['id_linoleum'] . ")")[0]['weight'];
    $list["price"] = [
        "fact" => 100
    ];
    $collection = getData("SELECT id_collection FROM linoleum WHERE id = " . $list['id_linoleum'])[0]['id_collection'];
    $data = getData("SELECT id FROM linoleum_width WHERE id_linoleum = " . $list['id_linoleum']);
    $sum = 0;
    foreach ($data as $elem) {
        $sum += getData("SELECT MAX(price) as price FROM linoleum_price WHERE id_linoleum_width =" . $elem['id'])[0]['price'];
    }
    $num = query("SELECT id FROM linoleum_width WHERE id_linoleum = " . $list['id_linoleum'], "result")->num_rows;
    if ($num != 0) {
        $avg = $sum / $num;
    }
    $data = 0;
    $data = getData("SELECT * FROM `linoleum_allowances` WHERE id_collection = $collection")[0];
    $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM linoleum_price WHERE id_linoleum_width in (SELECT id FROM linoleum_width Where id_linoleum = ' . $list['id_linoleum'] . "))");
    if (count($list['providers']) == 0) {
        $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            foreach ($list['width']['all'] as $width) {
                $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id'], 'width' => $width];
            }
        }
    } else {
        $list["purchase_price_list"] = getData('SELECT price as purchase_price, id_provider, width FROM linoleum_price, linoleum_width WHERE linoleum_width.id = id_linoleum_width AND id_linoleum = ' . $list['id_linoleum']);
    }
    $list["provider"] = $list["providers"][0]['id'];
    foreach ($list["purchase_price_list"] as $width) {
        if ($width['width'] == $list["width"]["select"]) {
            $list['purchase_price'] = $width['purchase_price'];
        }
    }

    $list["price"]['rrc']['all'] = [
        'p0_10' => round($avg + ($avg * ($data['0_10'] / 100)), 2),
        'p11_15' => round($avg + ($avg * ($data['11_15'] / 100)), 2),
        'p16_20' => round($avg + ($avg * ($data['16_20'] / 100)), 2),
        'p21_30' => round($avg + ($avg * ($data['21_30'] / 100)), 2),
        'p31_40' => round($avg + ($avg * ($data['31_40'] / 100)), 2),
        'p41_55' => round($avg + ($avg * ($data['41_55'] / 100)), 2),
        'p56_70' => round($avg + ($avg * ($data['56_70'] / 100)), 2),
    ];

    $list["price"]["fact"] = $list["price"]['rrc']['all']['p0_10'];
    $list["price"]['rrc']['selected'] = $list["price"]['rrc']['all']['p0_10'];
}
if (isset($_GET['id_plinth'])) {
    $list['key'] = $_GET['key'] + 1;
    $list['id_plinth'] = $_GET['id_plinth'];
    $list['product'] =  "Плинтуса " . getData("SELECT name FROM plinth WHERE id = " . $_GET['id_plinth'])[0]['name'];
    $list['ei'] = "шт";
    $list['col_vo'] = 0;
    $list['price'] = getData("SELECT MAX(price) as price FROM plinth_price, plinth_collection, plinth WHERE id_plinth_collection = plinth.id_collection and plinth.id_collection = plinth_collection.id and plinth.id = " . $_GET['id_plinth'])[0]['price'];
    $list['weight'] = getData("SELECT `weight` FROM plinth_collection,plinth WHERE id_collection = plinth_collection.id and plinth.id = " . $_GET['id_plinth'])[0]['weight'];
    $list['final_price'] = $list['price'];
    $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM plinth_price WHERE id_plinth_collection in (SELECT id_collection FROM plinth WHERE id = ' . $list['id_plinth'] . '))');
    if (count($list['providers']) == 0) {
        $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id']];
        }
    } else {
        $list["purchase_price_list"] = getData('SELECT purchase_price, id_provider FROM plinth_price WHERE id_plinth_collection in (SELECT id_collection FROM plinth WHERE id = ' . $list['id_plinth'] . ')');
    }
    $list["provider"] = $list['providers'][0]['id'];
    $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
}
if (isset($_GET['id_accessories'])) {
    $list['key'] = $_GET['key'] + 1;
    $list['id_accessories'] = $_GET['id_accessories'];
    $list['product'] =  getData("SELECT concat(plinth_accessories.name,' ',plinth.name) as name  FROM plinth_accessories JOIN plinth ON id_plinth=plinth.id WHERE plinth_accessories.id = " . $_GET['id_accessories'])[0]['name'];
    $list['ei'] = "шт";
    $list['col_vo'] = 0;
    $list['price'] = getData("SELECT MAX(price) as price FROM plinth_accessories_price,plinth_accessories WHERE plinth_accessories.id = id_accessories AND id = " . $_GET['id_accessories'])[0]['price'];
    $list['final_price'] = $list['price'];
    $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM plinth_accessories_price WHERE id_accessories = ' . $list['id_accessories'] . ')');
    if (count($list['providers']) == 0) {
        $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id']];
        }
    } else {
        $list["purchase_price_list"] = getData('SELECT purchase_price, id_provider FROM plinth_accessories_price WHERE id_accessories = ' . $list['id_accessories']);
    }
    $list["provider"] = $list["providers"][0]['id'];
    $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
}
if (isset($_GET['id_doorstep'])) {
    $list['key'] = $_GET['key'] + 1;
    $list['id_doorstep'] = $_GET['id_doorstep'];
    $list['product'] =  "Порог " . getData("SELECT concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color AND doorstep.id = " . $_GET['id_doorstep'])[0]['name'];
    $list['ei'] = "шт";
    $list['col_vo'] = 0;
    $list['price'] = getData("SELECT MAX(price) as price FROM doorstep_price WHERE  id_doorstep = " . $_GET['id_doorstep'])[0]['price'];
    $list['weight'] = 0;
    $list['final_price'] = $list['price'];
    $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM doorstep_price WHERE id_doorstep = ' . $list['id_doorstep'] . ')');
    if (count($list['providers']) == 0) {
        $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id']];
        }
    } else {
        $list["purchase_price_list"] = getData('SELECT purchase_price, id_provider FROM doorstep_price WHERE id_doorstep = ' . $list['id_doorstep']);
    }

    $list["provider"] = $elem['id_provider'];
    $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
}
echo json_encode($list, JSON_UNESCAPED_UNICODE);
