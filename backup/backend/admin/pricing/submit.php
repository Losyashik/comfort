<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once("../../librares/connect.php");
if (isset($_POST['table'])) {
    switch ($_POST['table']) {
        case "linoleum": {
                $all_width = $connect->getData("SELECT id FROM linoleum_width WHERE id_linoleum = " . $_POST['linoleum']);
                foreach ($all_width as $key => $item) {
                    $price = $connect->getData("SELECT id FROM linoleum_price WHERE id_linoleum_width = " . $item['id'] . " AND id_provider = " . $_POST['provider']);
                    if (in_array($item['id'], $_POST['width']) and count($price) == 0) {
                        $connect->query("INSERT INTO `linoleum_price`(`id_linoleum_width`, `id_provider`, `price`) VALUES ('" . $item['id'] . "','" . $_POST['provider'] . "','" . $_POST['width_price'][$item['id']] . "')");
                    } else if (in_array($item['id'], $_POST['width']) and count($price) != 0) {
                        $connect->query("UPDATE `linoleum_price` SET `price`='" . $_POST['width_price'][$item['id']] . "' WHERE id=" . $price[0]['id']);
                    } else if (!in_array($item['id'], $_POST['width']) and count($price) != 0) {
                        $connect->query("DELETE FROM `linoleum_price` WHERE id = " . $price[0]['id']);
                    }
                }

                $data['text'] = "Выполнено";
                $data['error'] = false;
                $data['active'] = true;
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            }
        case "plinth": {
                @$row = $connect->getData("SELECT price FROM plinth_price WHERE id_plinth_collection = " . $_POST['plinth_collection'] . " AND id_provider = " . $_POST['provider']);
                if (count($row) != 0) {
                    $connect->query("UPDATE `plinth_price` SET `price`='" . $_POST['rrc'] . "',`purchase_price`='" . $_POST['opt'] . "' WHERE id_plinth_collection = " . $_POST['plinth_collection'] . " AND id_provider = " . $_POST['provider']);
                } else {
                    $connect->query("INSERT INTO `plinth_price`(`id_plinth_collection`, `id_provider`, `price`, `purchase_price`) VALUES ('" . $_POST['plinth_collection'] . "','" . $_POST['provider'] . "','" . $_POST['rrc'] . "','" . $_POST['opt'] . "')");
                }
                $data['text'] = "Выполнено";
                $data['error'] = false;
                $data['active'] = true;
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            }
        case "accessories": {
                $accessories = $connect->getData("SELECT id FROM plinth_accessories WHERE id_plinth = " . $_POST['plinth']);
                if (isset($_POST['provider'])) {
                    foreach ($accessories as $key => $item) {
                        $price = $connect->getData("SELECT price as rrc, purchase_price as opt FROM plinth_accessories_price WHERE id_accessories = " . $item['id'] . " AND id_provider = " . $_POST['provider']);
                        if (count($price) != 0) {
                            $connect->query("UPDATE `plinth_accessories_price` SET `price`='" . $_POST['rrc_price'][$item['id']] . "',`purchase_price`='" . $_POST['opt_price'][$item['id']] . "' WHERE id_provider = '" . $_POST['provider'] . "' AND id_accessories = " . $item['id']);
                        } else {
                            $connect->query("INSERT INTO `plinth_accessories_price`(`id_accessories`, `id_provider`, `price`, `purchase_price`) VALUES ('" . $item['id'] . "','" . $_POST['provider'] . "','" . $_POST['rrc_price'][$item['id']] . "','" . $_POST['opt_price'][$item['id']] . "')");
                        }
                    }
                    $data['text'] = "Выполнено";
                    $data['error'] = false;
                    $data['active'] = true;
                    echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                }
            }
        case "doorstep": {
                @$row = $connect->getData("SELECT price FROM doorstep_price WHERE id_doorstep = " . $_POST['doorstep'] . " AND id_provider = " . $_POST['provider']);
                if (count($row) != 0) {
                    $connect->query("UPDATE `doorstep_price` SET `price`='" . $_POST['rrc'] . "',`purchase_price`='" . $_POST['opt'] . "' WHERE id_doorstep = " . $_POST['doorstep'] . " AND id_provider = " . $_POST['provider']);
                } else {
                    $connect->query("INSERT INTO `doorstep_price`(`id_doorstep`, `id_provider`, `price`, `purchase_price`) VALUES ('" . $_POST['doorstep'] . "','" . $_POST['provider'] . "','" . $_POST['rrc'] . "','" . $_POST['opt'] . "')");
                }
                $data['text'] = "Выполнено";
                $data['error'] = false;
                $data['active'] = true;
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            }
        case "related": {
                @$row = $connect->getData("SELECT price FROM related_price WHERE id_related = " . $_POST['related'] . " AND id_provider = " . $_POST['provider']);
                if (count($row) != 0) {
                    $connect->query("UPDATE `related_price` SET `price`='" . $_POST['rrc'] . "',`purchase_price`='" . $_POST['opt'] . "' WHERE id_related = " . $_POST['related'] . " AND id_provider = " . $_POST['provider']);
                } else {
                    $connect->query("INSERT INTO `related_price`(`id_related`, `id_provider`, `price`, `purchase_price`) VALUES ('" . $_POST['related'] . "','" . $_POST['provider'] . "','" . $_POST['rrc'] . "','" . $_POST['opt'] . "')");
                }
                $data['text'] = "Выполнено";
                $data['error'] = false;
                $data['active'] = true;
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            }
    }
}
