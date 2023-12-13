<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
if (isset($_GET['type'])) {
    include("./../librares/connect.php");
    if ($_GET['type'] == "measuring") {
        $data = $connect->getData("SELECT `orders`.`id`, `nick`, `number`, `note`,`street`, `order_cites`.`name` as `city`,`house`,`corpus`,`entrance`,`flat`, `measuring_time` as `time` FROM `orders`, `order_cites` WHERE `id_city`=`order_cites`.`id` AND `measuring_date` = '" . $_GET['date'] . "' ORDER BY `orders`.`measuring_time` IS NULL, `orders`.`measuring_time` ASC");
        foreach ($data as $key => $elem) {
            $addres = "";
            if ($elem['street'] != "") {
                $addres .= "ул. " . $elem['street'] . ", ";
                unset($data[$key]['street']);
            } else {
                unset($data[$key]['street']);
            }
            if ($elem['house'] != "") {
                $addres .= "д. " . $elem['house'] . ", ";
                unset($data[$key]['house']);
            } else {
                unset($data[$key]['house']);
            }
            if ($elem['corpus'] != "") {
                $addres .= "корп. " . $elem['corpus'] . ", ";
                unset($data[$key]['corpus']);
            } else {
                unset($data[$key]['corpus']);
            }
            if ($elem['entrance'] != "") {
                $addres .= $elem['entrance'] . " подъезд, ";
                unset($data[$key]['entrance']);
            } else {
                unset($data[$key]['entrance']);
            }
            if ($elem['flat'] != "") {
                $addres .= "кв. " . $elem['flat'] . ", ";
                unset($data[$key]['flat']);
            } else {
                unset($data[$key]['flat']);
            }
            if ($elem['time'] == "00:00:00") {
                unset($data[$key]['time']);
            }
            $data[$key]['addres'] = $addres;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    if ($_GET['type'] == "delivery") {
        $data = $connect->getData("SELECT `orders`.`id` as `id`, `sum`, `nick`, `orders`.`full_name` as `name`, `number`, `note`,`street`, `order_cites`.`name` as `city`,`house`,`corpus`,`entrance`,`flat` , `delivery_time` as `time` FROM `orders`, `order_cites` WHERE `id_city`=`order_cites`.`id`  AND `delivery_date` = '" . $_GET['date'] . "' ORDER BY `orders`.`delivery_time` IS NULL, `orders`.`delivery_time` ASC");
        foreach ($data as $key => $elem) {
            $addres = "";
            if ($elem['street'] != "") {
                $addres .= "ул. " . $elem['street'] . ", ";
                unset($data[$key]['street']);
            } else {
                unset($data[$key]['street']);
            }
            if ($elem['house'] != "") {
                $addres .= "д. " . $elem['house'] . ", ";
                unset($data[$key]['house']);
            } else {
                unset($data[$key]['house']);
            }
            if ($elem['corpus'] != "") {
                $addres .= "корп. " . $elem['corpus'] . ", ";
                unset($data[$key]['corpus']);
            } else {
                unset($data[$key]['corpus']);
            }
            if ($elem['entrance'] != "") {
                $addres .= $elem['entrance'] . " подъезд, ";
                unset($data[$key]['entrance']);
            } else {
                unset($data[$key]['entrance']);
            }
            if ($elem['flat'] != "") {
                $addres .= "кв. " . $elem['flat'] . ", ";
                unset($data[$key]['flat']);
            } else {
                unset($data[$key]['flat']);
            }
            if ($elem['time'] == "00:00:00") {
                unset($data[$key]['time']);
            }
            $data[$key]['addres'] = $addres;
            $list = $connect->getData("SELECT `id`, `id_linoleum`, `id_width`, `id_plinth`, `id_accessories`, `id_doorstep`,`id_related`, `col-vo`, `length`, `final_price` FROM `product_list` WHERE `id_order` = '" . $data[$key]['id'] . "' ORDER BY `product_list`.`id_linoleum` DESC, `product_list`.`id_plinth` DESC, `product_list`.`id_accessories` DESC, `product_list`.`id_doorstep` DESC");
            $list_key = 1;
            $product_list = [];
            foreach ($list as $k => $item) {
                if ($item['id_linoleum'] != null) {
                    $product_list[] = [
                        'key' => $list_key,
                        'name' => $connect->getData("SELECT `name` FROM linoleum_maker WHERE id = (SELECT id_maker FROM linoleum WHERE id = " . $item['id_linoleum'] . ")")[0]['name'] . " " . $connect->getData("SELECT `name` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $item['id_linoleum'] . ")")[0]['name'] . " " . $connect->getData("SELECT name FROM linoleum WHERE id = " . $item['id_linoleum'])[0]['name'] . " (" . $connect->getData("SELECT width FROM linoleum_width WHERE id = " . $item['id_width'])[0]['width'] . " х " . $item['length'] . ")",
                        'col_vo' => round($connect->getData("SELECT width FROM linoleum_width WHERE id = " . $item['id_width'])[0]['width'] * $item['length'], 2),
                        'final_price' => $item['final_price']
                    ];
                }
                if ($item['id_plinth'] != null) {
                    $product_list[] = [
                        'key' => $list_key,
                        'name' => "Плинтуса " . $connect->getData("SELECT name FROM plinth WHERE id = " . $item['id_plinth'])[0]['name'],
                        'col_vo' => $item['col-vo'],
                        'final_price' => $item['final_price']
                    ];
                }
                if ($item['id_accessories'] != null) {
                    $product_list[] = [
                        'key' => $list_key,
                        'name' => $connect->getData("SELECT name FROM plinth_accessories WHERE id = " . $item['id_accessories'])[0]['name'],
                        'col_vo' => $item['col-vo'],
                        'final_price' => $item['final_price']
                    ];
                }
                if ($item['id_doorstep'] != null) {
                    $product_list[] = [
                        'key' => $list_key,
                        'name' => "Порог " . $connect->getData("SELECT concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color AND doorstep.id = " . $item['id_doorstep'])[0]['name'],
                        'col_vo' => $item['col-vo'],
                        'final_price' => $item['final_price']
                    ];
                }
                if ($item['id_related'] != null) {
                    $product_list[] = [
                        'key' => $list_key,
                        'name' => "Порог " . $connect->getData("SELECT `name` FROM related WHERE  related.id = " . $item['id_related'])[0]['name'],
                        'col_vo' => $item['col-vo'],
                        'final_price' => $item['final_price']
                    ];
                }
                $list_key++;
            }
            $data[$key]['product_list'] = $product_list;
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
