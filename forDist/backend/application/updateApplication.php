<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('../librares/connect.php');
session_start();

$data = file_get_contents('php://input') ?? $_POST;
$data = json_decode($data, true);

$id_user = !isset($_SESSION['user']) ? 1 : $_SESSION['user']['id'];

if (isset($data['add'])) {
    $sql = "INSERT INTO `orders`(`nick`, `full_name`, `id_connection`, `id_city`, `street`, `house`, `corpus`, `entrance`, `flat`, `number`, `id_status`, `note`,`sum`,`weight`,`square`,`purchase`) VALUES ('" . $data['nick'] . "','" . $data["full_name"] . "','" . $data["connection"] . "','" . $data['city'] . "','" . $data['street'] . "','" . $data['house'] . "','" . $data['corpus'] . "','" . $data['entrance'] . "','" . $data['flat'] . "','" . $data['number'] . "','" . $data['status'] . "','" . $data['note'] . "','" . $data['sum'] . "','" . $data['weight'] . "','" . $data['square'] . "','" . $data['purchase'] . "')";

    $link = $connect->query($sql);

    $data["id"] = $link->insert_id;

    $stat_id =  $data['status'];
    $connect->query("INSERT INTO `order_history` (`id_status`, `id_order`,id_user) VALUES ('$stat_id', '" . $data["id"] . "', $id_user)");
    if (isset($data['no_1c'])) {
        $connect->query("UPDATE `orders` SET no_order_1c = '" . $data['no_1c'] . "' WHERE id = " . $data["id"]);
    }

    if ($data['payment_method'] != 0) {
        $connect->query("UPDATE `orders` SET payment_method = '" . $data['payment_method'] . "' WHERE id = " . $data["id"]);
    }
    if (isset($data['measuring_date'])) {
        $date = $data['measuring_date'] == "0000-00-00" ?  "NULL" : "'" . $data['measuring_date'] . "'";
        $connect->query("UPDATE `orders` SET measuring_date = $date WHERE id = " . $data["id"]);
    }

    if (isset($data['delivery_date'])) {
        $date = $data['delivery_date'] == "0000-00-00" ?  "NULL" : "'" . $data['delivery_date'] . "'";
        $connect->query("UPDATE `orders` SET delivery_date = $date WHERE id = " . $data["id"]);
    }
    if (isset($data['measuring_time'])) {
        $date = $data['measuring_time'] == "00:00" ?  "NULL" : "'" . $data['measuring_time'] . "'";
        $connect->query("UPDATE `orders` SET measuring_time = $date WHERE id = " . $data["id"]);
    }

    if (isset($data['delivery_time'])) {
        $date = $data['delivery_time'] == "00:00" ?  "NULL" : "'" . $data['delivery_time'] . "'";
        $connect->query("UPDATE `orders` SET delivery_time = $date WHERE id = " . $data["id"]);
    }

    if (isset($data['expectation'])) {
        $date = $data['expectation'] == "0000-00-00" ?  "NULL" : "'" . $data['expectation'] . "'";
        $connect->query("UPDATE `orders` SET expectation = $date WHERE id = " . $data["id"]);
    }
    foreach ($data['productList']['linoleum'] as $key => $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_width`, `id_linoleum`, `length`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',(SELECT id FROM linoleum_width WHERE id_linoleum="' . $elem['id_linoleum'] . '" AND width = "' . $elem['width']['select'] . '"),"' . $elem['id_linoleum'] . '","' . $elem['len'] . '","' . $elem['price']['fact'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = $connect->query($sql);
        $data['productList']['linolium'][$key]['id'] = $link->insert_id;
    }

    foreach ($data['productList']['plinth'] as $key =>  $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_plinth`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',"' . $elem['id_plinth'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = $connect->query($sql);
        $data['productList']['plinth'][$key]['id'] = $link->insert_id;
    }

    foreach ($data['productList']['accessories'] as $key =>  $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_accessories`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',"' . $elem['id_accessories'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = $connect->query($sql);
        $data['productList']['a'][$key]['id'] = $link->insert_id;
    }

    foreach ($data['productList']['doorstep'] as $key =>  $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_doorstep`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',"' . $elem['id_doorstep'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = $connect->query($sql);
        $data['productList']['doorstep'][$key]['id'] = $link->insert_id;
    }

    foreach ($data['productList']['related'] as $key =>  $elem) {
        $sql = 'INSERT INTO `product_list`( `id_order`, `id_related`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',"' . $elem['id_related'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
        $link = $connect->query($sql);
        $data['productList']['related'][$key]['id'] = $link->insert_id;
    }

    unset($data['add']);
    $data['seved'] = true;
    $dataPage['modal'] = ['text' => 'Заявка добавлена', 'error' => false, 'active' => true];
} else if (isset($data['update'])) {
    $sql = "UPDATE `orders` SET `nick`='" . $data['nick'] . "',`full_name`='" . $data["full_name"] . "',`id_connection`='" . $data["connection"] . "',`id_city`='" . $data['city'] . "',`street`='" . $data['street'] . "',`house`='" . $data['house'] . "',`corpus`='" . $data['corpus'] . "',`entrance`='" . $data['entrance'] . "',`flat`='" . $data['flat'] . "',`number`='" . $data['number'] . "',`id_status`='" . $data['status'] . "',`note`='" . $data['note'] . "',`weight`='" . $data['weight'] . "',`sum`='" . $data['sum'] . "', `square` ='" . $data['square'] . "', `purchase` = '" . $data['purchase'] . "' WHERE id = " . $data["id"];

    $connect->query($sql);

    $stat_id =  $data['status'];
    $last_stat_id = $connect->query("SELECT id_status FROM order_history WHERE `datetime` = (SELECT MAX(datetime) FROM order_history WHERE id_order = " . $data["id"] . ")", 'result')->num_rows == 0 ? 0 : $connect->getData("SELECT id_status FROM order_history WHERE `datetime` = (SELECT MAX(datetime) FROM order_history WHERE id_order = " . $data["id"] . ")")[0]['id_status'];
    if ($stat_id != $last_stat_id) {
        $connect->query("INSERT INTO `order_history` (`id_status`, `id_order`, `id_user`) VALUES ('$stat_id', '" . $data["id"] . "', $id_user)");
    }
    if (isset($data['no_1c'])) {
        $connect->query("UPDATE `orders` SET no_order_1c = '" . $data['no_1c'] . "' WHERE id = " . $data["id"]);
    }
    if ($data['payment_method'] != 0) {
        $connect->query("UPDATE `orders` SET payment_method = '" . $data['payment_method'] . "' WHERE id = " . $data["id"]);
    }
    if (isset($data['measuring_date'])) {
        $date = $data['measuring_date'] == "0000-00-00" ?  "NULL" : "'" . $data['measuring_date'] . "'";
        $connect->query("UPDATE `orders` SET measuring_date = $date WHERE id = " . $data["id"]);
    }

    if (isset($data['delivery_date'])) {
        $date = $data['delivery_date'] == "0000-00-00" ?  "NULL" : "'" . $data['delivery_date'] . "'";
        $connect->query("UPDATE `orders` SET delivery_date = $date WHERE id = " . $data["id"]);
    }
    if (isset($data['measuring_time'])) {
        $date = $data['measuring_time'] == "00:00" ?  "NULL" : "'" . $data['measuring_time'] . "'";
        $connect->query("UPDATE `orders` SET measuring_time = $date WHERE id = " . $data["id"]);
    }

    if (isset($data['delivery_time'])) {
        $date = $data['delivery_time'] == "00:00" ?  "NULL" : "'" . $data['delivery_time'] . "'";
        $connect->query("UPDATE `orders` SET delivery_time = $date WHERE id = " . $data["id"]);
    }

    if (isset($data['expectation'])) {
        $date = $data['expectation'] == "0000-00-00" ?  "NULL" : "'" . $data['expectation'] . "'";
        $connect->query("UPDATE `orders` SET expectation = $date WHERE id = " . $data["id"]);
    }

    $sql = "SELECT id FROM product_list WHERE id_order = " . $data["id"];
    $result = $connect->query($sql, 'result');

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
        $connect->query($sql);
    }

    foreach ($data['productList']['linoleum'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_width`=(SELECT id FROM linoleum_width WHERE id_linoleum="' . $elem['id_linoleum'] . '" AND width = "' . $elem['width']['select'] . '"),`id_linoleum`="' . $elem['id_linoleum'] . '",`length`="' . $elem['len'] . '",`final_price`= "' . $elem['price']['fact'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            $connect->query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_width`, `id_linoleum`, `length`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',(SELECT id FROM linoleum_width WHERE id_linoleum="' . $elem['id_linoleum'] . '" AND width = "' . $elem['width']['select'] . '"),"' . $elem['id_linoleum'] . '","' . $elem['len'] . '","' . $elem['price']['fact'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = $connect->query($sql);
            $data['productList']['linoleum'][$key]['id'] = $link->insert_id;
        }
    }

    foreach ($data['productList']['plinth'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_plinth`="' . $elem['id_plinth'] . '",`col-vo`="' . $elem['col_vo'] . '",`final_price`= "' . $elem['final_price'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            $connect->query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_plinth`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',"' . $elem['id_plinth'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = $connect->query($sql);
            $data['productList']['plinth'][$key]['id'] = $link->insert_id;
        }
    }

    foreach ($data['productList']['accessories'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_accessories`="' . $elem['id_accessories'] . '",`col-vo`="' . $elem['col_vo'] . '",`final_price`= "' . $elem['final_price'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            $connect->query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_accessories`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',"' . $elem['id_accessories'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = $connect->query($sql);
            $data['productList']['accessories'][$key]['id'] = $link->insert_id;
        }
    }

    foreach ($data['productList']['doorstep'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_doorstep`="' . $elem['id_doorstep'] . '",`col-vo`="' . $elem['col_vo'] . '",`final_price`= "' . $elem['final_price'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            $connect->query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_doorstep`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',"' . $elem['id_doorstep'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = $connect->query($sql);
            $data['productList']['doorstep'][$key]['id'] = $link->insert_id;
        }
    }
    foreach ($data['productList']['related'] as $key => $elem) {
        if (isset($elem['id'])) {
            $sql = 'UPDATE `product_list` SET `id_related`="' . $elem['id_related'] . '",`col-vo`="' . $elem['col_vo'] . '",`final_price`= "' . $elem['final_price'] . '",`purchase_price` =  "' . $elem['purchase_price'] . '",id_provider = "' . $elem['provider'] . '" WHERE id = ' . $elem['id'];
            $connect->query($sql);
        } else {
            $sql = 'INSERT INTO `product_list`( `id_order`, `id_related`, `col-vo`, `final_price`,`purchase_price`,`id_provider`) VALUES (' . $data["id"] . ',"' . $elem['id_related'] . '","' . $elem['col_vo'] . '","' . $elem['final_price'] . '","' . $elem['purchase_price'] . '","' . $elem['provider'] . '")';
            $link = $connect->query($sql);
            $data['productList']['related'][$key]['id'] = $link->insert_id;
        }
    }

    $dataPage['modal'] = ['text' => 'Заявка сохранена', 'error' => false, 'active' => true];
}
$dataPage['dataPage'] = $data;


echo json_encode($dataPage, JSON_UNESCAPED_UNICODE);
