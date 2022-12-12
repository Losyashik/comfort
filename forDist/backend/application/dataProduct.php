<?php
// header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('../librares/connect.php');
$list = [];
if (isset($_GET['id_linoleum'])) {
    $list['key'] = $_GET['key'] + 1;
    $list['id_linoleum'] = $_GET['id_linoleum'];
    $list['product'] = $connect->getData("SELECT `name` FROM linoleum_maker WHERE id = (SELECT id_maker FROM linoleum WHERE id = " . $list['id_linoleum'] . ")")[0]['name'] . " " . $connect->getData("SELECT `name` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $list['id_linoleum'] . ")")[0]['name'] . " " . $connect->getData("SELECT name FROM linoleum WHERE id = " . $_GET['id_linoleum'])[0]['name'];
    $list['ei'] = "м<sup>2</sup>";
    $list['width']['all'] = [];
    foreach ($connect->getData("SELECT width FROM linoleum_width WHERE id_linoleum = " . $_GET['id_linoleum']) as $width) {
        $list["width"]['all'][] = $width['width'];
    }
    $list["width"]["select"] = $list["width"]['all'][0];
    $list["len"] = 0;
    $list["weight"] = $connect->getData("SELECT `weight` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $list['id_linoleum'] . ")")[0]['weight'];
    $list["price"] = [
        "fact" => 100
    ];
    $collection = $connect->getData("SELECT id_collection FROM linoleum WHERE id = " . $list['id_linoleum'])[0]['id_collection'];
    $data = $connect->getData("SELECT id FROM linoleum_width WHERE id_linoleum = " . $list['id_linoleum']);
    $sum = 0;
    foreach ($data as $elem) {
        $sum += $connect->getData("SELECT MAX(price) as price FROM linoleum_price WHERE id_linoleum_width =" . $elem['id'])[0]['price'];
    }
    $num = $connect->query("SELECT id FROM linoleum_width WHERE id_linoleum = " . $list['id_linoleum'], "result")->num_rows;
    if ($num != 0) {
        $avg = $sum / $num;
    }
    $data = 0;
    $data = $connect->getData("SELECT * FROM `linoleum_allowances` WHERE id_collection = $collection")[0];
    $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM linoleum_price WHERE id_linoleum_width in (SELECT id FROM linoleum_width Where id_linoleum = ' . $list['id_linoleum'] . "))");
    if (count($list['providers']) == 0) {
        $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            foreach ($list['width']['all'] as $width) {
                $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id'], 'width' => $width];
            }
        }
    } else {
        $list["purchase_price_list"] = $connect->getData('SELECT price as purchase_price, id_provider, width FROM linoleum_price, linoleum_width WHERE linoleum_width.id = id_linoleum_width AND id_linoleum = ' . $list['id_linoleum']);
    }
    $list["provider"] = $list["providers"][0]['id'];
    foreach ($list["purchase_price_list"] as $width) {
        if ($width['width'] == $list["width"]["select"]) {
            $list['purchase_price'] = $width['purchase_price'];
        }
    }

    $data_width = $connect->getData("SELECT id FROM linoleum_width WHERE id_linoleum = " . $list['id_linoleum']);
    $sum = 0;
    foreach ($data_width as $elem) {
        $sum += $connect->getData("SELECT MAX(price) as price FROM linoleum_price WHERE id_linoleum_width =" . $elem['id'])[0]['price'];
    }
    $num = $connect->query("SELECT id FROM linoleum_width WHERE id_linoleum = " . $list['id_linoleum'], "result")->num_rows;
    if ($num != 0) {
        $avg = $sum / $num;
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
    $list['product'] =  "Плинтуса " . $connect->getData("SELECT name FROM plinth WHERE id = " . $_GET['id_plinth'])[0]['name'];
    $list['ei'] = "шт";
    $list['col_vo'] = 0;
    $list['price'] = $connect->getData("SELECT MAX(price) as price FROM plinth_price, plinth_collection, plinth WHERE id_plinth_collection = plinth.id_collection and plinth.id_collection = plinth_collection.id and plinth.id = " . $_GET['id_plinth'])[0]['price'];
    $list['weight'] = $connect->getData("SELECT `weight` FROM plinth_collection,plinth WHERE id_collection = plinth_collection.id and plinth.id = " . $_GET['id_plinth'])[0]['weight'];
    $list['final_price'] = $list['price'];
    $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM plinth_price WHERE id_plinth_collection in (SELECT id_collection FROM plinth WHERE id = ' . $list['id_plinth'] . '))');
    if (count($list['providers']) == 0) {
        $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id']];
        }
    } else {
        $list["purchase_price_list"] = $connect->getData('SELECT purchase_price, id_provider FROM plinth_price WHERE id_plinth_collection in (SELECT id_collection FROM plinth WHERE id = ' . $list['id_plinth'] . ')');
    }
    $list["provider"] = $list['providers'][0]['id'];
    $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
}
if (isset($_GET['id_accessories'])) {
    $list['key'] = $_GET['key'] + 1;
    $list['id_accessories'] = $_GET['id_accessories'];
    $list['product'] =  $connect->getData("SELECT concat(plinth_accessories.name,' ',plinth.name) as name  FROM plinth_accessories JOIN plinth ON id_plinth=plinth.id WHERE plinth_accessories.id = " . $_GET['id_accessories'])[0]['name'];
    $list['ei'] = "шт";
    $list['col_vo'] = 0;
    $list['price'] = $connect->getData("SELECT MAX(price) as price FROM plinth_accessories_price,plinth_accessories WHERE plinth_accessories.id = id_accessories AND id = " . $_GET['id_accessories'])[0]['price'];
    $list['final_price'] = $list['price'];
    $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM plinth_accessories_price WHERE id_accessories = ' . $list['id_accessories'] . ')');
    if (count($list['providers']) == 0) {
        $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id']];
        }
    } else {
        $list["purchase_price_list"] = $connect->getData('SELECT purchase_price, id_provider FROM plinth_accessories_price WHERE id_accessories = ' . $list['id_accessories']);
    }
    $list["provider"] = $list["providers"][0]['id'];
    $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
}
if (isset($_GET['id_doorstep'])) {
    $list['key'] = $_GET['key'] + 1;
    $list['id_doorstep'] = $_GET['id_doorstep'];
    $list['product'] =  "Порог " . $connect->getData("SELECT concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color AND doorstep.id = " . $_GET['id_doorstep'])[0]['name'];
    $list['ei'] = "шт";
    $list['col_vo'] = 0;
    $list['price'] = $connect->getData("SELECT MAX(price) as price FROM doorstep_price WHERE  id_doorstep = " . $_GET['id_doorstep'])[0]['price'] == null ? "0.00" : $connect->getData("SELECT MAX(price) as price FROM doorstep_price WHERE  id_doorstep = " . $_GET['id_doorstep'])[0]['price'];
    $list['weight'] = 0;
    $list['final_price'] = $list['price'];
    $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM doorstep_price WHERE id_doorstep = ' . $list['id_doorstep'] . ')');
    if (count($list['providers']) == 0) {
        $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id']];
        }
    } else {
        $list["purchase_price_list"] = $connect->getData('SELECT purchase_price, id_provider FROM doorstep_price WHERE id_doorstep = ' . $list['id_doorstep']);
    }

    $list["provider"] = $list["purchase_price_list"][0]['id_provider'];
    $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
}
if (isset($_GET['id_related'])) {
    $list['key'] = $_GET['key'] + 1;
    $list['id_related'] = $_GET['id_related'];
    $list['product'] =  $connect->getData("SELECT name FROM related WHERE id =" . $_GET['id_related'])[0]['name'];
    $list['ei'] = $connect->getData("SELECT ei FROM related WHERE id =" . $_GET['id_related'])[0]['ei'];
    $list['col_vo'] = 0;
    $list['price'] = $connect->getData("SELECT MAX(price) as price FROM related_price WHERE  id_related = " . $_GET['id_related'])[0]['price'] == null ? "0.00" : $connect->getData("SELECT MAX(price) as price FROM related_price WHERE  id_related = " . $_GET['id_related'])[0]['price'];
    $list['weight'] = 0;
    $list['final_price'] = $list['price'];
    $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM related_price WHERE id_related = ' . $list['id_related'] . ')');
    if (count($list['providers']) == 0) {
        $list["providers"] = $connect->getData('SELECT id,short_name as name  FROM provider WHERE 1');
        foreach ($list["providers"] as $elem) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $elem['id']];
        }
    } else {
        $list["purchase_price_list"] = $connect->getData('SELECT purchase_price, id_provider FROM related_price WHERE id_related = ' . $list['id_related']);
    }

    $list["provider"] = $list["purchase_price_list"][0]['id_provider'];
    $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
}

// if (isset($_GET['warehouse_id'])) {
//     $data = array_filter(
//         $connect->getData("SELECT * from `warehouse_goods` WHERE id = " . $_GET['warehouse_id'])[0],
//         function ($var) {
//             return $var !== null;
//         }
//     );
//     foreach ($data as $key => $elem) {
//         if ($key == "id_linoleum") {
//             $list['product'] = $connect->getData("SELECT concat(`linoleum_maker`.`name`,' ',`linoleum_collection`.`name`,' ',`linoleum`.`name`) as product FROM linoleum JOIN `linoleum_maker` ON `linoleum_maker`.`id` = `linoleum`.`id` JOIN `linoleum_collection` ON `linoleum_collection`.`id` = `linoleum`.`id` WHERE `linoleum`.`id` = " . $elem)[0]["product"];
//             $list['ei'] = "м<sup>2</sup>";
//             $list["weight"] = $connect->getData("SELECT `weight` FROM `linoleum_collection` JOIN `linoleum` ON linoleum_collection.id = linoleum.id")[0]['weight'];
//             $list["len"] = $data['length'];
//             $list['width']['all'] = [
//                 "width" => $connect->getData("SELECT `width` FROM `linoleum_width` WHERE id = " . $data['id_width']),
//                 "purchase_price" => $data["purchase_price"],
//             ];
//             $list["width"]["select"] = $connect->getData("SELECT `width` FROM `linoleum_width` WHERE `id` = " . $data['id_width'])[0]['width'];
//             $list['provider'] = $data['id_provider'];
//             $list["price"]['rrc']['all'] = [
//                 'p0_10' => round($avg + ($avg * ($data['0_10'] / 100)), 2),
//                 'p11_15' => round($avg + ($avg * ($data['11_15'] / 100)), 2),
//                 'p16_20' => round($avg + ($avg * ($data['16_20'] / 100)), 2),
//                 'p21_30' => round($avg + ($avg * ($data['21_30'] / 100)), 2),
//                 'p31_40' => round($avg + ($avg * ($data['31_40'] / 100)), 2),
//                 'p41_55' => round($avg + ($avg * ($data['41_55'] / 100)), 2),
//                 'p56_70' => round($avg + ($avg * ($data['56_70'] / 100)), 2),
//             ];

//             $list["price"]["fact"] = $list["price"]['rrc']['all']['p0_10'];
//             $list["price"]['rrc']['selected'] = $list["price"]['rrc']['all']['p0_10'];
//         } else if ($key == "id_plinth") {
//         } else if ($key == "id_accesories") {
//         } else if ($key == "id_doorstep") {
//         }
//     }

//     // arrayLog($list);
// }

echo json_encode($list, JSON_UNESCAPED_UNICODE);
