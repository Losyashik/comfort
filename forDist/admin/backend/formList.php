<?php
$request_body = file_get_contents('php://input');
$data_page = json_decode($request_body, true);
include_once('./../../backend/librares/connect.php');

if ($data_page['product'] == '')
    if ($data_page['type'] == "price" or $data_page['type'] == "allowances")
        $data = getData("SELECT `id`, `name` FROM linoleum_collection");
    else
    if ($data_page['type'] == "recommended")
        $data = getData("SELECT concat(id_linoleum,'_',id_plinth) as id, concat(linoleum.name, ' &#8660; ',plinth.name) as name FROM linoleum,plinth,recommended WHERE id_linoleum = linoleum.id AND id_plinth=plinth.id;");
    else
        $data = getData("SELECT id,name FROM " . $data_page['type']);
else
    $data = getData("SELECT id,name FROM " . $data_page['product'] . "_" . $data_page['type']);
echo  json_encode($data, JSON_UNESCAPED_UNICODE);
