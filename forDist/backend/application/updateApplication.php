<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('../librares/connect.php');
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
if (isset($data['add'])) {
    $sql = "INSERT INTO `orders`(`nick`, `full_name`, `id_connection`, `id_city`, `street`, `house`, `corpus`, `entrance`, `flat`, `number`, `id_status`, `note`,`sum`,`weight`,`square`,`purchase`) VALUES ('" . $data['nick'] . "','" . $data['initials'] . "',(SELECT id FROM order_connection WHERE name = '" . $data['toc'] . "'),(SELECT id FROM order_cites WHERE name = '" . $data['city'] . "'),'" . $data['street'] . "','" . $data['house'] . "','" . $data['corpus'] . "','" . $data['entrance'] . "','" . $data['flat'] . "','" . $data['number'] . "',(SELECT id FROM order_status WHERE name = '" . $data['status'] . "'),'" . $data['note'] . "','" . $data['sum'] . "','" . $data['weight'] . "','" . $data['square'] . "','" . $data['purchase'] . "')";
    $link = query($sql);
    $data['no'] = $link->insert_id;
    if (isset($data['measuring_date'])) {
        query("UPDATE `orders` SET measuring_date = '" . $data['measuring_date'] . "' WHERE id = " . $data['no']);
    }
    if (isset($data['delivery_date'])) {
        query("UPDATE `orders` SET delivery_date = '" . $data['delivery_date'] . "' WHERE id = " . $data['no']);
    }
    if (isset($data['expectation'])) {
        query("UPDATE `orders` SET expectation = '" . $data['expectation'] . "' WHERE id = " . $data['no']);
    }
    foreach ($data['productList']['linoleum'] as $key => $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_width`, `id_linoleum`, `length`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data['no'] . ',(SELECT id FROM linoleum_width WHERE id_linoleum="' . $elem['id_linoleum'] . '" AND width = "' . $elem['width']['select'] . '"),"' . $elem['id_linoleum'] . '","' . $elem['len'] . '","' . $elem['price']['fact'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = query($sql);
        $data['productList']['linolium'][$key]['id'] = $link->insert_id;
    }
    foreach ($data['productList']['plinth'] as $key =>  $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_plinth`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data['no'] . ',"' . $elem['id_plinth'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = query($sql);
        $data['productList']['plinth'][$key]['id'] = $link->insert_id;
    }
    foreach ($data['productList']['accessories'] as $key =>  $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_accessories`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data['no'] . ',"' . $elem['id_accessories'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = query($sql);
        $data['productList']['a'][$key]['id'] = $link->insert_id;
    }
    foreach ($data['productList']['doorstep'] as $key =>  $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_doorstep`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data['no'] . ',"' . $elem['id_doorstep'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = query($sql);
        $data['productList']['doorstep'][$key]['id'] = $link->insert_id;
    }
    unset($data['add']);
    $dataPage['modal'] = ['text' => 'Заявка добавлена', 'error' => false, 'active' => true];
} else if (isset($data['update'])) {
    $sql = "UPDATE `orders` SET `nick`='" . $data['nick'] . "',`full_name`='" . $data['initials'] . "',`id_connection`=(SELECT id FROM order_connection WHERE name = '" . $data['toc'] . "'),`id_city`=(SELECT id FROM order_cites WHERE name = '" . $data['city'] . "'),`street`='" . $data['street'] . "',`house`='" . $data['house'] . "',`corpus`='" . $data['corpus'] . "',`entrance`='" . $data['entrance'] . "',`flat`='" . $data['flat'] . "',`number`='" . $data['number'] . "',`id_status`=(SELECT id FROM order_status WHERE name = '" . $data['status'] . "'),`note`='" . $data['note'] . "',`weight`='" . $data['weight'] . "',`sum`='" . $data['sum'] . "', `square` ='" . $data['square'] . "', `purchase` = '" . $data['purchase'] . "' WHERE id = " . $data['no'];
    query($sql);
    $stat_id = getData("SELECT id FROM order_status WHERE name = '" . $data['status'] . "'")[0]['id'];
    $last_stat_id = query("SELECT id_status FROM order_history WHERE `datetime` = (SELECT MAX(datetime) FROM order_history WHERE id_order = " . $data['no'] . ")", 'result')->num_rows == 0 ? 0 : getData("SELECT id_status FROM order_history WHERE `datetime` = (SELECT MAX(datetime) FROM order_history WHERE id_order = " . $data['no'] . ")")[0]['id_status'];
    if ($stat_id != $last_stat_id) {
        query("INSERT INTO `order_history` (`id_status`, `id_order`) VALUES ('$stat_id', '" . $data['no'] . "')");
    }
    if (isset($data['measuring_date'])) {
        query("UPDATE `orders` SET measuring_date = '" . $data['measuring_date'] . "' WHERE id = " . $data['no']);
    }
    if (isset($data['delivery_date'])) {
        query("UPDATE `orders` SET delivery_date = '" . $data['delivery_date'] . "' WHERE id = " . $data['no']);
    }
    if (isset($data['expectation'])) {
        query("UPDATE `orders` SET expectation = '" . $data['expectation'] . "' WHERE id = " . $data['no']);
    }
    $sql = "SELECT id FROM product_list WHERE id_order = " . $data['no'];
    $result = query($sql, 'result');
    for ($id_list = []; $row = $result->fetch_assoc(); $id_list[] = $row['id']);
    foreach ($data['productList'] as $elem) {
        foreach ($elem as $row) {
            if (isset($row['id'])) {
                if (in_array($row['id'], $id_list)) {
                    unset($id_list[array_search($row['id'], $id_list)]);
                }
            }
        }
    }
    foreach ($id_list as $id) {
        $sql = "DELETE FROM product_list WHERE id = $id";
        query($sql);
    }
    foreach ($data['productList']['linoleum'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_width`=(SELECT id FROM linoleum_width WHERE id_linoleum="' . $elem['id_linoleum'] . '" AND width = "' . $elem['width']['select'] . '"),`id_linoleum`="' . $elem['id_linoleum'] . '",`length`="' . $elem['len'] . '",`final_price`= "' . $elem['price']['fact'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_width`, `id_linoleum`, `length`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data['no'] . ',(SELECT id FROM linoleum_width WHERE id_linoleum="' . $elem['id_linoleum'] . '" AND width = "' . $elem['width']['select'] . '"),"' . $elem['id_linoleum'] . '","' . $elem['len'] . '","' . $elem['price']['fact'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = query($sql);
            $data['productList']['linoleum'][$key]['id'] = $link->insert_id;
        }
    }
    foreach ($data['productList']['plinth'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_plinth`="' . $elem['id_plinth'] . '",`col-vo`="' . $elem['col_vo'] . '",`final_price`= "' . $elem['final_price'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_plinth`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data['no'] . ',"' . $elem['id_plinth'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = query($sql);
            $data['productList']['plinth'][$key]['id'] = $link->insert_id;
        }
    }
    foreach ($data['productList']['accessories'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_accessories`="' . $elem['id_accessories'] . '",`col-vo`="' . $elem['col_vo'] . '",`final_price`= "' . $elem['final_price'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_accessories`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data['no'] . ',"' . $elem['id_accessories'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = query($sql);
            $data['productList']['accessories'][$key]['id'] = $link->insert_id;
        }
    }
    foreach ($data['productList']['doorstep'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_doorstep`="' . $elem['id_doorstep'] . '",`col-vo`="' . $elem['col_vo'] . '",`final_price`= "' . $elem['final_price'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_doorstep`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data['no'] . ',"' . $elem['id_doorstep'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = query($sql);
            $data['productList']['doorstep'][$key]['id'] = $link->insert_id;
        }
    }
    $dataPage['modal'] = ['text' => 'Заявка сохранена', 'error' => false, 'active' => true];
}
$dataPage['dataPage'] = $data;


echo json_encode($dataPage, JSON_UNESCAPED_UNICODE);
