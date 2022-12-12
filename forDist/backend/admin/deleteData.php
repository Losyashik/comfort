<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../../backend/librares/connect.php');
switch ($_POST['table_name']) {
    case "linoleum": {
            foreach ($_POST['item'] as $item) {
                $query = "DELETE FROM `linoleum_width` WHERE id_linoleum = $item;";
                $connect->query($query);
                $data = $connect->getData("SELECT id, src FROM linoleum_images WHERE id_linoleum = $item");
                foreach ($data as $elem) {
                    if (unlink("../." . $elem['src'])) {
                        $query = "DELETE FROM `linoleum_images` WHERE id = " . $elem['id'] . ";";
                        $connect->query($query);
                    } else {
                        $query = "DELETE FROM `linoleum_images` WHERE id = " . $elem['id'] . ";";
                        $connect->query($query);
                    }
                }
                $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id = $item;";
                $connect->query($query);
            }
            $data['text'] = "Удалено";
            $data['error'] = false;
            $data['active'] = true;
            echo  json_encode($data, JSON_UNESCAPED_UNICODE);
            break;
        }
    case "doorstep": {
            foreach ($_POST['item'] as $item) {
                $query = $connect->getData("SELECT src FROM doorstep WHERE id = $item")[0]['src'];
                if (unlink("../." . $query)) {
                    $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id = $item;";
                }


                $connect->query($query);
            }
            $data['text'] = "Удалено";
            $data['error'] = false;
            $data['active'] = true;
            echo  json_encode($data, JSON_UNESCAPED_UNICODE);
            break;
        }
    case "plinth": {
            foreach ($_POST['item'] as $item) {
                $query = $connect->getData("SELECT src FROM plinth WHERE id = $item")[0]['src'];
                if (unlink("../." . $query)) {
                    $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id = $item;";
                }
                $connect->query($query);
            }
            $data['text'] = "Удалено";
            $data['error'] = false;
            $data['active'] = true;
            echo  json_encode($data, JSON_UNESCAPED_UNICODE);
            break;
        }
    case "recommended": {
            foreach ($_POST['item'] as $item) {
                $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id_linoleum = " . substr($item, 0, strpos($item, '_')) . " AND id_plinth=" . substr($item, strpos($item, '_') + 1);
                $connect->query($query);
            }
            $data['text'] = "Удалено";
            $data['error'] = false;
            $data['active'] = true;
            echo  json_encode($data, JSON_UNESCAPED_UNICODE);
            break;
        }
    default: {
            foreach ($_POST['item'] as $item) {
                $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id = $item;";
                //$connect->query($query);
            }
            $data['text'] = "Удалено";
            $data['error'] = false;
            $data['active'] = true;
            echo  json_encode($data, JSON_UNESCAPED_UNICODE);
        }
}
