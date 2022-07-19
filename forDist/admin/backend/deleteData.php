<?php
include_once('./../../backend/librares/connect.php');
switch ($_POST['table_name']) {
    case "linoleum": {
            foreach ($_POST['item'] as $item) {
                $query = "DELETE FROM `linoleum_width` WHERE id_linoleum = $item;";
                query($query);
                $data = getData("SELECT id, src FROM linoleum_images WHERE id_linoleum = $item");
                foreach($data as $elem){
                    if(unlink("../.".$elem['src']))
                        $query = "DELETE FROM `linoleum_images` WHERE id = ".$elem['id'].";";
                        query($query);
                }
                $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id = $item;";
                query($query);
            }
            break;
        }
    case "plinth": {
            foreach ($_POST['item'] as $item) {
                $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id = $item;";
                query($query);
            }
            break;
        }
    case "recommended":{
        foreach ($_POST['item'] as $item) { 
            $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id_linoleum = ".substr($item,0,strpos($item,'_'))." AND id_plinth=".substr($item,strpos($item,'_')+1);
            query($query);
        }
        break;
    }
    default: {
            foreach ($_POST['item'] as $item) {
                $query = "DELETE FROM `" . $_POST['table_name'] . "` WHERE id = $item;";
            }
        }
}
