<?php
include_once('./../../backend/librares/connect.php');
if (isset($_POST['name']) and !is_array($_POST['name'])) {
    while (true) {
        if ($_POST['name'][0] == " ") {
            $_POST['name'] = substr($_POST['name'], 1, strlen($_POST['name']));
        } else if ($_POST['name'][strlen($_POST['name']) - 1] == " ") {
            $_POST['name'] = substr($_POST['name'], 0, strlen($_POST['name']) - 1);
        } else {
            break;
        }
    }
}
switch ($_POST['table_name']) {
    case "linoleum_allowances": {
            $query = "UPDATE `linoleum_allowances` SET";
            foreach ($_POST as $key => $value) {
                if ($key != "table_name" and $key != "collection") {
                    $query .= " `$key` = '$value',";
                }
            }
            $query[strlen($query) - 1] = ' ';
            query($query . "WHERE id_collection = " . $_POST['collection']);
            break;
        }
    case "price": {
            foreach ($_POST as $key => $value) {
                if ($key != "table_name" and $key != "collection") {
                    $query = "UPDATE `linoleum_width` SET `price`='$value' WHERE width = '" . str_replace('_', '.', $key) . "' AND id_collection = '" . $_POST['collection'] . "';";
                    query($query);
                }
            }
            break;
        }
    case "linoleum": {
            $query = "INSERT INTO " . $_POST['table_name'] . " SET ";
            foreach ($_POST as $key => $value) {
                if ($key != "table_name" and $key != "width")
                    $query .= "$key = '$value',";
            }
            $query[strlen($query) - 1] = ';';
            $link = query($query);
            $last_id = $link->insert_id;
            if (isset($_POST['width'])) {
                $query = "INSERT INTO linoleum_width (`width`,`id_linoleum`,`id_collection`) VALUES ";

                foreach ($_POST['width'] as $value) {
                    $query .= " ('$value','$last_id','" . $_POST['id_collection'] . "'),";
                }
                $query[strlen($query) - 1] = ';';
                query($query);
            }
            if ($_FILES['images']["error"][0] != 4) {
                $images = getArrFiles($_FILES['images']);
                foreach ($images as $key => $elem) {
                    $type = substr($elem['name'], strripos($elem['name'], "."), strlen($elem['name']));
                    $name = translite($_POST['name']) . $key . $type;
                    $collection = translite(getData("SELECT name FROM linoleum_collection WHERE id = " . $_POST['id_collection'])[0]['name']);
                    $path = "./img/poduct_images/linoleum/$collection/$name";
                    if (move_uploaded_file($elem['tmp_name'], "../.$path")) {
                        $query = "INSERT INTO linoleum_images (`id_linoleum`,`src`) VALUES ('$last_id','$path')";
                        query($query);
                    }
                }
            }
            break;
        }
    case "plinth": {
            if ($_FILES['images']["error"][0] != 4) {
                $images = getArrFiles($_FILES['images'])[0];

                $type = substr($images['name'], strripos($images['name'], "."), strlen($images['name']));
                $name = translite($_POST['name']) . $type;
                $collection = translite(getData("SELECT name FROM plinth_collection WHERE id = " . $_POST['id_collection'])[0]['name']);
                $path = "./img/poduct_images/plinth/$collection/$name";
                if (move_uploaded_file($images['tmp_name'], "../.$path")) {
                    $query = "INSERT INTO " . $_POST['table_name'] . " SET ";
                    foreach ($_POST as $key => $value) {
                        if ($key != "table_name" and $key != "width")
                            $query .= "`$key` = '$value',";
                    }
                    $query .= "`src` = '$path';";
                    query($query);
                }
            }
            break;
        }
    case "linoleum_collection":
    case "plinth_collection": {
            $product = substr($_POST['table_name'], 0, strpos($_POST['table_name'], "_"));
            if (!mkdir("../../img/poduct_images/$product/" . translite($_POST['name']))) {
                echo "ERROR: Directory not created";
                break;
            }
            $query = "INSERT INTO " . $_POST['table_name'] . " SET ";
            foreach ($_POST as $key => $value) {
                if ($key != "table_name")
                    $query .= "$key = '$value',";
            }
            $query[strlen($query) - 1] = ';';
            $collection_id = query($query)->insert_id;

            if ($_POST['table_name'] == "linoleum_collection") {
                query("INSERT INTO `linoleum_allowances`(`id_collection`, `0_10`, `11_15`, `16_20`, `21_30`, `31_40`, `41_55`, `56_70`) VALUES ('$collection_id','0','0','0','0','0','0','0')");
            }
            break;
        }
    default: {
            $query = "INSERT INTO " . $_POST['table_name'] . " SET ";
            foreach ($_POST as $key => $value) {
                if ($key != "table_name")
                    $query .= "$key = '$value',";
            }
            $query[strlen($query) - 1] = ';';
            query($query);
            break;
        }
}
