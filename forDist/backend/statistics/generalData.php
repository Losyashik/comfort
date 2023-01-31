<?php
// header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../librares/connect.php');

if (isset($_GET['start']))
    if (isset($_GET['end'])) {
        $orders = " WHERE orders.id IN (SELECT id_order as id FROM order_history WHERE (id_status = 7 OR id_status = 8) AND `datetime` >= '" . $_GET['start'] . " 00:00:00' AND `datetime` <= '" . $_GET['end'] . " 23:59:59')";
        $where = " WHERE orders.id IN (SELECT id_order as id FROM order_history WHERE id_status = 7 AND `datetime` >= '" . $_GET['start'] . " 00:00:00' AND `datetime` <= '" . $_GET['end'] . " 23:59:59')";
    } else {
        $orders = " WHERE orders.id IN (SELECT id_order as id FROM order_history WHERE (id_status = 7 OR id_status = 8) AND `datetime` >= '" . $_GET['start'] . " 00:00:00' AND `datetime` <= '" . $_GET['start'] . " 23:59:59')";
        $where = " WHERE orders.id IN (SELECT id_order as id FROM order_history WHERE id_status = 7 AND `datetime` >= '" . $_GET['start'] . " 00:00:00' AND `datetime` <= '" . $_GET['start'] . " 23:59:59')";
    }
else {
    $orders = "";
    $where = "";
}

$space = $connect->getData("SELECT `name`, `width`, SUM(`length` * `width`) AS `space` FROM `orders`, `linoleum`, `product_list`, `linoleum_width` WHERE `product_list`.`id_width` = `linoleum_width`.`id` AND `product_list`.`id_linoleum` = `linoleum`.`id` AND `orders`.`id` = `id_order` AND id_order IN (SELECT id FROM orders  $where) GROUP BY `width`, `name` ORDER BY `name`,`width` ASC, `space` DESC;");

$space_result = [];
foreach ($space as $elem) {
    $arName = array_filter($space, function ($v) use ($elem) {
        return $v['name'] ==  $elem['name'];
    });
    $item = [];
    $sum = 0;
    foreach ($arName as $k => $el) {
        $sum += round($el['space'], 3);
        $item[] = ["name" => $el['width'], "count" => round($el['space'], 3)];
    };
    if (count(array_filter($space_result, function ($v) use ($elem) {
        return $v['name'] ==  $elem['name'];
    })) == 0)
        $space_result[] = [
            'name' => $elem['name'],
            'items' => $item,
            'count' => round($sum, 3),
            'ei' => "м<sup>2</sup>",
            'bol' => false,
        ];
}

$accessories = $connect->getData("SELECT `plinth`.`name` AS `plinth`, `p_a`.`name` AS `name`, SUM(`col-vo`) AS count FROM `product_list`, `plinth`, `plinth_accessories` AS `p_a` WHERE `id_accessories` = `p_a`.`id` AND `plinth`.`id` = `p_a`.`id_plinth` AND NOT `id_accessories` IS NULL AND id_order IN (SELECT id FROM orders $where) GROUP BY `plinth`, `name`; ");

$accessories_result = [];

foreach ($accessories as $elem) {
    $arName = array_filter($accessories, function ($v) use ($elem) {
        return $v['plinth'] ==  $elem['plinth'];
    });
    $item = [];
    $sum = 0;
    foreach ($arName as $k => $el) {
        $sum += round($el['count'], 3);
        $item[] = ["name" => $el['name'], "count" => round($el['count'], 3)];
    };
    if (count(array_filter($accessories_result, function ($v) use ($elem) {
        return $v['name'] ==  $elem['plinth'];
    })) == 0)
        $accessories_result[] = [
            'name' => $elem['plinth'],
            'items' => $item,
            'count' => round($sum, 3),
            'ei' => "шт.",
            'bol' => false,
        ];
}

$data['general'] = [
    'orders' => $connect->getData("SELECT COUNT(id) as cnt FROM orders $orders")[0]['cnt'],
    'space' => $connect->getData("SELECT SUM(square) as cnt FROM orders  $where")[0]['cnt'],
    'plinth' => $connect->getData("SELECT SUM(`col-vo`) as cnt FROM product_list WHERE NOT id_plinth  IS NULL AND id_order IN (SELECT id FROM orders $where)")[0]['cnt'],
    'accessories' => $connect->getData("SELECT SUM(`col-vo`) as cnt FROM product_list WHERE NOT id_accessories  IS NULL AND id_order IN (SELECT id FROM orders  $where)")[0]['cnt'],
    'doorstep' => $connect->getData("SELECT SUM(`col-vo`) as cnt FROM product_list WHERE NOT id_doorstep  IS NULL AND id_order IN (SELECT id FROM orders  $where)")[0]['cnt'],
    'related' => $connect->getData("SELECT SUM(`col-vo`) as cnt FROM product_list WHERE NOT id_related  IS NULL AND id_order IN (SELECT id FROM orders  $where)")[0]['cnt'],
];
$data['all'] = [
    'orders' => $connect->getData("SELECT name, COUNT(orders.id) as cnt, 'заявок' as ei FROM order_status,orders WHERE id_status = order_status.id AND orders.id IN ( SELECT id FROM orders $orders) GROUP BY name ORDER BY order_status.id;"),
    'space' => $space_result,
    'plinth' => $connect->getData("SELECT `name`, SUM(`col-vo`) AS cnt, 'шт.' as ei FROM plinth, product_list WHERE id_plinth = plinth.id AND id_order IN (SELECT id FROM orders  $where) GROUP BY `name` ORDER BY `cnt` DESC"),
    'accessories' => $accessories_result,
    'doorstep' => $connect->getData("SELECT CONCAT( doorstep_maker.name, ' ', doorstep_color.name, ' (', width, '*', doorstep_size.length, '*', depth, ')' ) AS `name`, SUM(`col-vo`) AS cnt, 'шт.' AS ei FROM doorstep, doorstep_maker, doorstep_size, doorstep_color, product_list WHERE id_doorstep = doorstep.id AND id_size = doorstep_size.id AND id_maker = doorstep_maker.id AND id_color = doorstep_color.id AND id_order IN (SELECT id FROM orders  $where) GROUP BY `name` ORDER BY `name` "),
    'related' => $connect->getData("SELECT `name`, SUM(`col-vo`) AS cnt, 'шт.' as ei FROM related, product_list WHERE id_related = related.id AND id_order IN (SELECT id FROM orders  $where) GROUP BY `name` ORDER BY `name` "),
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);
