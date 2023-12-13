<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");

include_once('./../../../backend/librares/connect.php');

if (isset($_POST['user'])) {
    if (isset($_POST['rights'])) {
        $query = "DELETE FROM rights_users WHERE id_user = " . $_POST['user'] . " AND NOT id_right IN (";
        $sql = "INSERT INTO rights_users (id_user, id_right) VALUES ";
        foreach ($_POST['rights'] as $item) {
            $query .= $item . ",";
            if ($connect->query("SELECT * FROM rights_users WHERE id_user =" . $_POST['user'] . " AND id_right = $item", "result")->num_rows == 0) {
                $sql .= "(" . $_POST['user'] . ",$item),";
            }
        }
        if ($sql != "INSERT INTO rights_users (id_user, id_right) VALUES ") {
            $sql[strlen($sql) - 1] = ';';
            $connect->query($sql);
        }
        $query[strlen($query) - 1] = ')';
        $connect->query($query);
    } else {
        $connect->query("DELETE FROM rights_users WHERE id_user = " . $_POST['user']);
    }
    $data['text'] = "Привелегии обновлены";
    $data['error'] = false;
    $data['active'] = true;
    echo  json_encode($data, JSON_UNESCAPED_UNICODE);
}
