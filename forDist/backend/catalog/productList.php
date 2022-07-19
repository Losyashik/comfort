<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../librares/connect.php');
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
if ($data['warehouse'] == "true") {
    if ($data['type'] == "Linoleum") {
        if (count($data) != 2) {
            $query = "SELECT DISTINCT linoleum.id as id,name,src FROM linoleum, linoleum_images WHERE linoleum.id = id_linoleum  ";

            foreach ($data as $key => $elem) {
                if ($key == "type" or $key == "warehouse") {
                    continue;
                } else
            if ($key == "search") {
                    $query .= " AND `name` LIKE '%" . $elem . "%'";
                    continue;
                } else
            if (($key == "total_thickness" or $key == "protective") and $key != "width") {
                    $query .= " AND id_collection IN(SELECT id FROM linoleum_collection WHERE `$key` IN (";
                } else 
            if ($key == "width") {
                    $query .= " AND linoleum.id IN (SELECT id_linoleum FROM linoleum_width WHERE width in (";
                } else {
                    $query .= " AND id_collection IN (SELECT id FROM linoleum_collection WHERE 1 AND id_$key IN (";
                }
                foreach ($elem as $i) {
                    $query .= "'$i',";
                }
                $query[strlen($query) - 1] = ')';
                $query .= ")";
            }
            $query .= " GROUP BY id";
        } else {
            $query = "SELECT DISTINCT linoleum.id as id,name,src FROM linoleum, linoleum_images WHERE linoleum.id = id_linoleum GROUP BY id";
        }
    } else if ($data['type'] == "Plintusa") {
        if (count($data) != 2) {
            $query = "SELECT id,name,src FROM plinth WHERE 1";
            foreach ($data as $key => $elem) {
                if ($key == "type" or $key == "warehouse") {
                    continue;
                } else
            if ($key == "search") {
                    $query .= " AND `name` LIKE '%" . $elem . "%'";
                    continue;
                } else

        if ($key == 'heigth') {
                    $query .= " AND id_collection in (SELECT id FROM plinth_collection WHERE heigth in (";
                    foreach ($elem as $id) {
                        $query .= "$id,";
                    }
                    $query[strlen($query) - 1] = ')';
                    $query .= ')';
                } else {
                    $query .= " AND id_$key IN (";
                    foreach ($elem as $id) {
                        $query .= "'$id',";
                    }
                    $query[strlen($query) - 1] = ')';
                }
            }
            $query .= " GROUP BY id";
        } else {
            $query = "SELECT id,name,src FROM plinth";
        }
    } else if ($data['type'] == "Porogi") {
        if (count($data) != 2) {
            $query = "SELECT doorstep.id as id, concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color";
            foreach ($data as $key => $elem) {
                if ($key == "type" or $key == "warehouse") {
                    continue;
                } else 
            if ($key == "search") {
                    $query .= " AND `name` LIKE '%" . $elem . "%'";
                    continue;
                } else {
                    $query .= " AND id_$key IN (";
                    foreach ($elem as $id) {
                        $query .= "'$id',";
                    }
                    $query[strlen($query) - 1] = ')';
                }
            }
            $query .= " GROUP BY id";
        } else {
            $query = "SELECT doorstep.id as id, concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color";
        }
    }
} else {
    if ($data['type'] == "Linoleum") {
        $query = "SELECT \n"
            . "	linoleum.id, \n"
            . "	concat(linoleum_maker.name,' ',linoleum_collection.name,' ',linoleum.name) as `name`, \n"
            . "    concat(linoleum_width.width,' x ',length) as size \n"
            . "FROM warehouse_goods \n"
            . "    JOIN linoleum ON id_linoleum = linoleum.id\n"
            . "    JOIN linoleum_collection ON id_collection = linoleum_collection.id\n"
            . "    JOIN linoleum_maker ON linoleum.id_maker = linoleum_maker.id\n"
            . "    JOIN linoleum_width ON warehouse_goods.id_width = linoleum_width.id;";
    } elseif ($data['type'] == "Plintusa") {
    } elseif ($data['type'] == "Furnitura") {
    } else if ($data['type'] == "Porogi") {
    }
}
$data = getData($query);
echo  json_encode($data, JSON_UNESCAPED_UNICODE);
