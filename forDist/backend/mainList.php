<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./librares/connect.php');
$request_body = file_get_contents('php://input');
$data_query = json_decode($request_body, true);
$mainList = [];
session_start();
if (isset($_SESSION['user'])) {
  $queryEnd = " AND id_status NOT IN (SELECT id FROM order_status WHERE name IN (";
  if (!in_array(7, $_SESSION['user']['rights'])) {
    $queryEnd .= "'Подготовка заказа'";
    if (!in_array(8, $_SESSION['user']['rights'])) {
      $queryEnd .= ",'Доставка'";
      if (!in_array(9, $_SESSION['user']['rights'])) {
        $queryEnd .= ",'Завершённый'))";
      } else {
        $queryEnd .= "))";
      }
    } else {
      $queryEnd .= "))";
    }
  } else if (!in_array(8, $_SESSION['user']['rights'])) {
    $queryEnd .= "'Доставка'";
    if (!in_array(9, $_SESSION['user']['rights'])) {
      $queryEnd .= ",'Завершённый'))";
    } else {
      $queryEnd .= "))";
    }
  } else if (!in_array(9, $_SESSION['user']['rights'])) {
    $queryEnd .= "'Завершённый'))";
  } else {
    $queryEnd = "";
  }
} else {
  $queryEnd = "";
}
if (count($data_query) != 0) {
  if (in_array("sorting", $data_query['opiration'])) {
    $query = "SELECT * FROM orders WHERE 1 ";
    foreach ($data_query as $key => $elem) {
      if ($key != "opiration" and $key != "type" and $key != "text" and $key != "select_date") {
        $query .= "AND id_$key IN (";
        foreach ($elem as $id) {
          $query .= "$id,";
        }
        $query[strlen($query) - 1] = ')';
      }
      if ($key == "select_date") {
        foreach ($elem as $id) {
          switch ($id) {
            case 0: {
                $query .= "AND measuring_date IS NOT NULL ";
                break;
              }
            case 1: {
                $query .= "AND measuring_date IS NULL AND id_status in(SELECT id FROM order_status WHERE name='Замер')";
                break;
              }
            case 2: {
                $query .= "AND delivery_date IS NOT NULL ";
                break;
              }
            case 3: {
                $query .= "AND delivery_date IS NULL AND id_status in(SELECT id FROM order_status WHERE name='Доставка')";
                break;
              }
          }
        }
      }
    }
    if (in_array("search", $data_query['opiration'])) {
      $query .= "AND " . $data_query['type'] . " LIKE '%" . $data_query['text'] . "%' ";
    }
    $query;
  } else if (in_array("search", $data_query['opiration'])) {
    $query = "SELECT * FROM orders WHERE " . $data_query['type'] . " LIKE '" . $data_query['text'] . "%' ";
  }
} else {
  $query = "SELECT * FROM orders WHERE id_status NOT IN (SELECT id FROM order_status WHERE name IN('Отказ','Завершённый'))";
}
$query .= $queryEnd;
$query .= " ORDER BY `time` DESC";
$data = getData($query);
foreach ($data as $key => $elem) {
  if ($elem['id_city'] != null) {
    $city = getData('Select name from order_cites Where id = ' . $elem['id_city'])[0]['name'];
  } else {
    $city = "";
  }

  $mainList[$key] =
    [
      'nick' => $elem['nick'],
      'initials' => $elem['full_name'],
      'status' => getData("select name from order_status where id=" . $elem['id_status'])[0]['name'],
      'number' => $elem['number'],
      'toc' => getData('Select name from order_connection Where id = ' . $elem['id_connection'])[0]['name'],
      'note' => $elem['note'],
      'no' => $elem['id'],
      'city' => $city,
      'street' => $elem['street'],
      'house' => $elem['house'],
      'corpus' => $elem['corpus'],
      'entrance' => $elem['entrance'],
      'flat' => $elem['flat'],
      'sum' => $elem['sum'],
      'weight' => $elem['weight'],
      'purchase' => $elem['purchase'],
      'square' => $elem['square'],
      'lastKey' => getData("SELECT count(id) FROM product_list WHERE id_order = " . $elem['id'])[0]['count(id)'],

    ];
  if ($elem['measuring_date'] != null) {
    $mainList[$key]['measuring_date'] = $elem['measuring_date'];
  }
  if ($elem['delivery_date'] != null) {
    $mainList[$key]['delivery_date'] = $elem['delivery_date'];
  }
  if ($elem['expectation'] != null) {
    $mainList[$key]['expectation'] = $elem['expectation'];
  }
}
echo json_encode($mainList, JSON_UNESCAPED_UNICODE);
