<?php
// header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../librares/connect.php');
$sql = "SELECT * FROM product_list WHERE id_order = " . $_GET['id'] . " Order by id_doorstep,id_accessories,id_plinth,id_linoleum DESC";
$data = getData($sql);
$productList['linoleum'] = [];
$productList['plinth'] = [];
$productList['accessories'] = [];
$productList['doorstep'] = [];
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
        $data = getData("SELECT id FROM linoleum_width WHERE id_linoleum = " . $elem['id_linoleum']);
        $sum_price = 0;
        foreach ($data as $el) {
            $sum_price += getData("SELECT MAX(price) as price FROM linoleum_price WHERE id_linoleum_width =" . $el['id'])[0]['price'];
        }
        $num = query("SELECT id FROM linoleum_width WHERE id_linoleum = " . $elem['id_linoleum'], "result")->num_rows;
        if ($num != 0) {
            $avg = $sum_price / $num;
        }
        $data = 0;
        $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM linoleum_price WHERE id_linoleum_width in (SELECT id FROM linoleum_width Where id_linoleum = ' . $list['id_linoleum'] . "))");
        if (count($list['providers']) == 0) {
            $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE 1');
            foreach ($list["providers"] as $el) {
                foreach ($list['width']['all'] as $width) {
                    $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id'], 'width' => $width];
                }
            }
        } else {
            $list["purchase_price_list"] = getData('SELECT price as purchase_price, id_provider, width FROM linoleum_price, linoleum_width WHERE linoleum_width.id = id_linoleum_width AND id_linoleum = ' . $list['id_linoleum']);
        }

        $list["provider"] = $elem['id_provider'];
        if ($elem['purchase_price'] == 0)
            foreach ($list["purchase_price_list"] as $width) {
                if ($width['width'] == $list["width"]['select']) {
                    $list['purchase_price'] = $width['purchase_price'];
                    break;
                }
            }

        else {
            $list["purchase_price"] = $elem['purchase_price'];
        }
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
        $productList['linoleum'][] = $list;
    }
    if ($elem['id_plinth'] != NULL) {
        $list['key'] = $i++;
        $list['id'] = $elem['id'];
        $list['id_plinth'] = $elem['id_plinth'];
        $list['product'] =  "Плинтуса " . getData("SELECT name FROM plinth WHERE id = " . $elem['id_plinth'])[0]['name'];
        $list['ei'] = "шт";
        $list['col_vo'] = $elem['col-vo'];
        $list['price'] = getData("SELECT MAX(price) as price FROM plinth_price, plinth WHERE plinth.id_collection = plinth_price.id_plinth_collection and plinth.id = " . $elem['id_plinth'])[0]['price'];
        if($list['price'] == null){
            $list['price'] = "0.00";
        }
        $list['weight'] = getData("SELECT `weight` FROM plinth_collection,plinth WHERE id_collection = plinth_collection.id and plinth.id = " . $elem['id_plinth'])[0]['weight'];
        $list['final_price'] = $elem['final_price'];
        $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM plinth_price WHERE id_plinth_collection in (SELECT id_collection FROM plinth WHERE id = ' . $elem['id_plinth'] . '))');
        if (count($list['providers']) == 0) {
            $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE 1');
            foreach ($list["providers"] as $el) {
                $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id']];
            }
        } else {
            $list["purchase_price_list"] = getData('SELECT purchase_price, id_provider FROM plinth_price WHERE id_plinth_collection in (SELECT id_collection FROM plinth WHERE id = ' . $elem['id_plinth'] . ')');
        }
        $list["provider"] = $elem['id_provider'];
        if ($elem['purchase_price'] == 0)
            $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
        else {
            $list["purchase_price"] = $elem['purchase_price'];
        }
        $productList['plinth'][] = $list;
    }
    if ($elem['id_accessories'] != NULL) {
        $list['key'] = $i++;
        $list['id'] = $elem['id'];
        $list['id_accessories'] = $elem['id_accessories'];
        $list['product'] =  getData("SELECT concat(plinth_accessories.name,' ',plinth.name) as name  FROM plinth_accessories JOIN plinth ON id_plinth=plinth.id WHERE plinth_accessories.id = " . $elem['id_accessories'])[0]['name'];
        $list['ei'] = "шт";
        $list['col_vo'] = $elem['col-vo'];
        $list['price'] = getData("SELECT MAX(price) as price FROM plinth_accessories_price WHERE  id_accessories = " . $elem['id_accessories'])[0]['price'];
        if($list['price'] == null){
            $list['price'] = "0.00";
        }
        $list['final_price'] = $elem['final_price'];
        $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM plinth_accessories_price WHERE id_accessories = ' . $elem['id_accessories'] . ')');
        if (count($list['providers']) == 0) {
            $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE 1');
            foreach ($list["providers"] as $el) {
                $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id']];
            }
        } else {
            $list["purchase_price_list"] = getData('SELECT purchase_price, id_provider FROM plinth_accessories_price WHERE id_accessories = ' . $elem['id_accessories']);
        }
        $list["provider"] = $elem['id_provider'];
        if ($elem['purchase_price'] == 0)
            $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
        else {
            $list["purchase_price"] = $elem['purchase_price'];
        }
        $productList['accessories'][] = $list;
    }
    if ($elem['id_doorstep'] != NULL) {
        $list['key'] = $i++;
        $list['id'] = $elem['id'];
        $list['id_doorstep'] = $elem['id_doorstep'];
        $list['product'] = "Порог " . getData("SELECT concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color AND doorstep.id = " . $elem['id_doorstep'])[0]['name'];
        $list['ei'] = "шт";
        $list['col_vo'] = $elem['col-vo'];
        $list['price'] = getData("SELECT MAX(price) as price FROM doorstep_price WHERE  id_doorstep = " . $elem['id_doorstep'])[0]['price'];
        if($list['price'] == null){
            $list['price'] = "0.00";
        }
        $list['final_price'] = $elem['final_price'];
        $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE id in (SELECT id_provider FROM doorstep_price WHERE id_doorstep = ' . $elem['id_doorstep'] . ')');
        if (count($list['providers']) == 0) {
            $list["providers"] = getData('SELECT id,short_name as name  FROM provider WHERE 1');
            foreach ($list["providers"] as $el) {
                $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id']];
            }
        } else {
            $list["purchase_price_list"] = getData('SELECT purchase_price, id_provider FROM doorstep_price WHERE id_doorstep = ' . $elem['id_doorstep']);
        }
        $list["provider"] = $elem['id_provider'];
        if ($elem['purchase_price'] == 0)
            $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
        else {
            $list["purchase_price"] = $elem['purchase_price'];
        }
        $productList['doorstep'][] = $list;
    }
}
if ($sum > 0 && $sum <= 10) $rKey = "p0_10";
else if ($sum <= 15) $rKey = "p11_15";
else if ($sum <= 20) $rKey = "p16_20";
else if ($sum <= 30) $rKey = "p21_30";
else if ($sum <= 40) $rKey = "p31_40";
else if ($sum <= 55) $rKey = "p41_55";
else $rKey = "p56_70";
foreach ($productList['linoleum'] as $key => $elem) {
    $productList['linoleum'][$key]["price"]['rrc']['selected'] =  $productList['linoleum'][$key]["price"]['rrc']['all'][$rKey];
}
echo json_encode($productList, JSON_UNESCAPED_UNICODE);
