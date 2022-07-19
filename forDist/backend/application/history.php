<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once("./../librares/connect.php");

if (isset($_GET['id'])) {
    $data = getData("SELECT name, datetime as start_date FROM order_history JOIN order_status as status ON id_order = " . $_GET['id'] . "  AND id_status=status.id ORDER BY datetime ASC");
    for($i=0; $i<count($data)-1;$i++){
        $data[$i]['stop_date'] = $data[$i+1]['start_date'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
