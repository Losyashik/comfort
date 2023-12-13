<?php
// header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");

include_once('./../librares/connect.php');

$data['linoleum'] = $connect->getData("SELECT * FROM linoleum");

foreach ($data['linoleum'] as $key => $elem) {
    foreach ($connect->getData("SELECT src FROM linoleum_images WHERE id_linoleum = " . $elem['id']) as $img) {
        $data['linoleum'][$key]['images'][] = $img['src'];
    }
    $collection = $connect->getData("SELECT * FROM linoleum_collection WHERE id = " . $elem['id_collection'])[0];
    $data['linoleum'][$key]['collection_data'] = $collection;
    $data['linoleum'][$key]["class"] = $connect->getData("SELECT name FROM linoleum_class WHERE id = " . $collection['id_class'])[0]['name'];
    $data['linoleum'][$key]["totalThickness"] = $collection['total_thickness'];
    $data['linoleum'][$key]["type"] = $connect->getData("SELECT name FROM linoleum_type WHERE id = " . $collection['id_type'])[0]['name'];
    $data['linoleum'][$key]["protective"] = $collection['protective'];
    $data['linoleum'][$key]["base"] = $connect->getData("SELECT name FROM linoleum_base WHERE id = " . $collection['id_base'])[0]['name'];
    $data['linoleum'][$key]["maker"] = $connect->getData("SELECT name FROM linoleum_maker WHERE id = " . $collection['id_maker'])[0]['name'];
    $data['linoleum'][$key]["collection"] = $collection['name'];
    $data['linoleum'][$key]["destination"] = $connect->getData("SELECT name FROM linoleum_destination WHERE id = " . $collection['id_destination'])[0]['name'];
    $data['linoleum'][$key]["weight"] = $collection['weight'];
    $data['linoleum'][$key]["width"] = $connect->getData("SELECT id, width FROM linoleum_width WHERE id_linoleum = " . $elem['id']);
    foreach ($connect->getData("SELECT id FROM plinth WHERE id IN (SELECT id_plinth FROM recommended WHERE id_linoleum = " . $elem['id'] . ")") as $el) {
        $data['linoleum'][$key]["rec"][] = $el['id'];
    };
    $list = $connect->getData("SELECT id FROM linoleum_width WHERE id_linoleum = " . $elem['id']);
    $sum = 0;
    foreach ($list as $e) {
        $sum += $connect->getData("SELECT MAX(price) as price FROM linoleum_price WHERE id_linoleum_width =" . $e['id'])[0]['price'];
    }
    $num = $connect->query("SELECT id FROM linoleum_width WHERE id_linoleum = " . $elem['id'], "result")->num_rows;
    if ($num != 0) {
        $avg = $sum / $num;
    }
    $list = $connect->getData("SELECT * FROM `linoleum_allowances` WHERE id_collection = " . $elem['id_collection'])[0];
    $data['linoleum'][$key]["price"] = [
        "selected" => "",
        "all" => [
            'p0_10' => round($avg + ($avg * ($list['0_10'] / 100)), 2),
            'p11_15' => round($avg + ($avg * ($list['11_15'] / 100)), 2),
            'p16_20' => round($avg + ($avg * ($list['16_20'] / 100)), 2),
            'p21_30' => round($avg + ($avg * ($list['21_30'] / 100)), 2),
            'p31_40' => round($avg + ($avg * ($list['31_40'] / 100)), 2),
            'p41_55' => round($avg + ($avg * ($list['41_55'] / 100)), 2),
            'p56_70' => round($avg + ($avg * ($list['56_70'] / 100)), 2),
        ],
    ];
}

$data['plinth'] = $connect->getData("SELECT * FROM plinth");

foreach ($data['plinth'] as $key => $elem) {
    $elem['collection'] = $connect->getData("SELECT * FROM plinth_collection WHERE id = " . $elem['id_collection'])[0];
    $data['plinth'][$key]['images'] = [$elem['src']];
    $data['plinth'][$key]['maker'] = $connect->getData("SELECT name FROM plinth_maker WHERE id =" . $elem['id_maker'])[0]['name'];
    $data['plinth'][$key]['name'] = "Плинтус " . $elem['name'];
    $data['plinth'][$key]['collection'] = $elem['collection']['name'];
    $data['plinth'][$key]['width'] = $elem['collection']['width'];
    $data['plinth'][$key]['heigth'] = $elem['collection']['heigth'];
    $data['plinth'][$key]['len'] = $elem['collection']['length'];
    $data['plinth'][$key]['price'] = $connect->getData("SELECT MAX(price) as price FROM plinth_price WHERE id_plinth_collection = " . $elem['collection']['id'])[0]['price'];
    $list = $connect->getData("SELECT id, name FROM plinth_accessories WHERE id_plinth = " . $elem['id']);
    foreach ($list as $k => $item) {
        $item_price = $connect->getData("SELECT MAX(price) as price FROM plinth_accessories_price WHERE id_accessories = " . $item['id']);
        if (count($item_price) > 0) {
            $list[$k]['price'] = $item_price[0]['price'];
        } else {
            $list[$k]['price'] = 0;
        }
    }
    $data['plinth'][$key]['accessories'] = $list;
}



$data['doorstep'] = $connect->getData("SELECT doorstep.id as id, concat('Порог ',doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name`,id_size, id_maker,id_color FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color");

$data['related'] = $connect->getData("SELECT id, name, src FROM related");

echo json_encode($data, JSON_UNESCAPED_UNICODE);
