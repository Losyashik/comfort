<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../librares/connect.php');
if (isset($_GET['id_linoleum'])) {
    $data = getData("SELECT * FROM linoleum WHERE id = " . $_GET['id_linoleum']);
    $data = $data[0];
    foreach (getData("SELECT src FROM linoleum_images WHERE id_linoleum = " . $data['id']) as $elem) {
        $data['images'][] = $elem['src'];
    }
    $data['width'] = [];
    foreach (getData("SELECT width FROM linoleum_width WHERE id_linoleum = " . $data['id']) as $elem) {
        $data['width'][] = $elem['width'];
    }
    $data['collection'] = getData("SELECT id_class, id_type,total_thickness, protective,id_base, name, id_destination FROM linoleum_collection WHERE id =" . $data['id_collection'])[0];
    $data['recommended'] = getData("SELECT id,name,src FROM plinth WHERE id in (SELECT id_plinth FROM recommended WHERE id_linoleum = " . $data['id'] . ")");
    $list = getData("SELECT id FROM linoleum_width WHERE id_linoleum = " . $data['id']);
    $sum = 0;
    foreach ($list as $elem) {
        $sum += getData("SELECT MAX(price) as price FROM linoleum_price WHERE id_linoleum_width =" . $elem['id'])[0]['price'];
    }
    $num = query("SELECT id FROM linoleum_width WHERE id_linoleum = " . $_GET['id_linoleum'], "result")->num_rows;
    if ($num != 0) {
        $avg = $sum / $num;
    }
    $list = 0;
    $list = getData("SELECT * FROM `linoleum_allowances` WHERE id_collection = " . $data['id_collection'])[0];
    $array = [
        "id" => $data['id'],
        "name" => $data['name'],
        "images" => $data['images'],
        "class" => getData("SELECT name FROM `linoleum_class` WHERE id = " . $data['collection']['id_class'])[0]['name'],
        "totalThickness" => $data['collection']['total_thickness'],
        "type" => getData("SELECT name FROM `linoleum_type` WHERE id = " . $data['collection']['id_type'])[0]['name'],
        "protective" => $data['collection']['protective'],
        "base" => getData("SELECT name FROM `linoleum_base` WHERE id = " . $data['collection']['id_base'])[0]['name'],
        "maker" => getData("SELECT name FROM `linoleum_maker` WHERE id = " . $data['id_maker'])[0]['name'],
        "collection" =>  $data['collection']['name'],
        "destination" => getData("SELECT name FROM `linoleum_destination` WHERE id = " . $data['collection']['id_destination'])[0]['name'],
        "width" => $data['width'],
        "rec" => $data['recommended'],
        "price" => [
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
        ],
    ];
}
if (isset($_GET['id_plinth'])) {
    $data = getData("SELECT * FROM plinth WHERE id = " . $_GET['id_plinth'])[0];
    $data['collection'] = getData("SELECT * FROM plinth_collection WHERE id = " . $data['id_collection'])[0];
    $array =
        [
            "id" => $data['id'],
            'images' => [$data['src']],
            'name' => "Плинтус " . $data['name'],
            'maker' => getData("SELECT name FROM plinth_maker WHERE id =" . $data['id_maker'])[0]['name'],
            'collection' => $data['collection']['name'],
            'width' => $data['collection']['width'],
            'heigth' => $data['collection']['heigth'],
            'len' => $data['collection']['length'],
            'price' => getData("SELECT MAX(price) as price FROM plinth_price WHERE id_plinth_collection = " . $data['collection']['id'])[0]['price'],
        ];
    $list = getData("SELECT id, name FROM plinth_accessories WHERE id_plinth = " . $data['id']);
    foreach ($list as $key => $item) {
        $item_price = getData("SELECT MAX(price) as price FROM plinth_accessories_price WHERE id_accessories = " . $item['id']);
        if (count($item_price) > 0) {
            $list[$key]['price'] = $item_price[0]['price'];
        } else {
            $list[$key]['price'] = 0;
        }
    }
    $array['accessories'] = $list;
}
echo json_encode($array, JSON_UNESCAPED_UNICODE);
