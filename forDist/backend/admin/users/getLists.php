<?php
// header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");

include_once('./../../../backend/librares/connect.php');

if (isset($_POST['list'])) {
    echo json_encode(getData("SELECT id, name, 'false' as checked FROM users"), JSON_UNESCAPED_UNICODE);
}
if (isset($_POST['id_user'])) {
    $categoryes = getData("SELECT * FROM rights_category"); 
    $rights_user = getData("SELECT id_user, id_right FROM rights_users WHERE id_user = " . $_POST['id_user']);
    foreach ($categoryes as $category) {
        $rights =getData("SELECT id, name FROM rights WHERE id_category = ".$category['id']);
        foreach ($rights as $key => $elem) {
            foreach ($rights_user as $el) {
                if ($el['id_right'] == $elem['id']) {
                    $rights[$key]['checked'] = true;
                    break;
                } else {
                    $rights[$key]['checked'] = false;
                }
            }
        }
        $data[] = ['category'=> $category['name'], 'list' => $rights];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}