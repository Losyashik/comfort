<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
$request_body = file_get_contents('php://input');
$data_page = json_decode($request_body, true);
include_once('./../../backend/librares/connect.php');
if ($data_page['product'] == '') {
    if ($data_page['type'] == "linoleum" or $data_page['type'] == "plinth") {
        if (isset($data_page['sort'])) {
            $data['sort'] = [
                "maker" => getData("SELECT id,name FROM " . $data_page['type'] . "_maker"),
                "collection" => getData("SELECT id,name FROM " . $data_page['type'] . "_collection")
            ];
            if ($data_page['sort']['maker'] != 0) {
                $data['sort']["collection"] = getData("SELECT id,name FROM " . $data_page['type'] . "_collection WHERE id_maker=" . $data_page['sort']['maker']);
                if ($data_page['sort']['collection'] != 0) {
                    $data['sort']['maker'] = getData("SELECT id,name FROM " . $data_page['type'] . "_maker WHERE id in (SELECT id_maker FROM " . $data_page['type'] . "_collection WHERE id =" . $data_page['sort']['collection'] . ")");
                    $data['list'] = getData("SELECT id,name FROM " . $data_page['type'] . " WHERE id_collection = " . $data_page['sort']['collection'] . " AND `id_maker`=" . $data_page['sort']['maker']);
                } else
                    $data['list'] = getData("SELECT id,name FROM " . $data_page['type'] . " WHERE `id_maker`=" . $data_page['sort']['maker']);
            } else {
                if ($data_page['sort']['collection'] != 0) {
                    $data['sort']['maker'] = getData("SELECT id,name FROM " . $data_page['type'] . "_maker WHERE id in (SELECT id_maker FROM " . $data_page['type'] . "_collection WHERE id =" . $data_page['sort']['collection'] . ")");
                    $data['list'] = getData("SELECT id,name FROM " . $data_page['type'] . " WHERE id_collection = " . $data_page['sort']['collection']);
                }
            }
        } else {
            $data['sort'] = [
                "maker" => getData("SELECT id,name FROM " . $data_page['type'] . "_maker"),
                "collection" => getData("SELECT id,name FROM " . $data_page['type'] . "_collection")
            ];
            $data['list'] = getData("SELECT id,name FROM " . $data_page['type']);
        }
    } else
    if ($data_page['type'] == "recommended") {
        $data['list'] = getData("SELECT concat(id_linoleum,'_',id_plinth) as id, concat(linoleum.name, ' ⇔ ',plinth.name) as name FROM linoleum,plinth,recommended WHERE id_linoleum = linoleum.id AND id_plinth=plinth.id;");
    } else if ($data_page['type'] == "doorstep") {
        $data['sort'] = [];
        $data['list'] = getData("SELECT doorstep.id as id, concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color");
    }
} else if ($data_page['type'] == 'size') {
    $data['sort'] = [];
    $data['list'] = getData("SELECT id, concat(`width`,' * ',`length`,' * ',`depth`) as name FROM `doorstep_size`");
} else if ($data_page['type'] == 'collection') {
    $data['sort'] = ["maker" => getData("SELECT id,name FROM " . $data_page['product'] . "_maker")];
    if (isset($data_page['sort'])) {
        if ($data_page['sort']['maker'] != 0) {
            $data['sort'] = ["maker" => getData("SELECT id,name FROM " . $data_page['product'] . "_maker")];
            $data['list'] = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type'] . " WHERE id_maker = " . $data_page['sort']['maker']);
        } else {
            $data['sort'] = ["maker" => getData("SELECT id,name FROM " . $data_page['product'] . "_maker")];
            $data['list'] = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type']);
        }
    } else {
        $data['sort'] = ["maker" => getData("SELECT id,name FROM " . $data_page['product'] . "_maker")];
        $data['list'] = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type']);
    }
} else if ($data_page['type'] == 'accessories') {
    $data['sort'] = ["plinth" => getData("SELECT id,name FROM " . $data_page['product'])];
    if (isset($data_page['sort'])) {
        if ($data_page['sort']['plinth'] != 0) {
            $data['sort'] = ["plinth" => getData("SELECT id,name FROM " . $data_page['product'])];
            $data['list'] = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type'] . " WHERE id_plinth = " . $data_page['sort']['plinth']);
        } else {
            $data['sort'] = ["plinth" => getData("SELECT id,name FROM " . $data_page['product'])];
            $data['list'] = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type']);
        }
    } else {
        $data['sort'] = ["plinth" => getData("SELECT id,name FROM " . $data_page['product'])];
        $data['list'] = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type']);
    }
    // $data['list'] = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type']);
} else {
    $data['sort'] = [];

    $data['list'] = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type']);
}
echo  json_encode($data, JSON_UNESCAPED_UNICODE);
