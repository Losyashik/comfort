<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./librares/connect.php');
session_start();

$mainList = ["update" => true];
if (isset($_GET['edit'])) {
  $scan = true;
  while ($scan) {
    sleep(1.5);
    $lastUpdateBD = $connect->getData("SELECT MAX(last_update) as date FROM orders")[0]["date"];
    if ($_SESSION['last_update'] < strtotime($lastUpdateBD)) {
      $scan = false;
    } else if (time() - $_SERVER['REQUEST_TIME'] >= 3) {
      $scan = false;
      echo json_encode(["update" => 0, "list" => []], JSON_UNESCAPED_UNICODE);
      exit();
    }
  }
}

if ($mainList["update"]) {

  if (isset($_GET['limit'])) {
    $orderList = $connect->getData("SELECT * FROM orders WHERE !(id_status IN (7,8)) ORDER BY id DESC LIMIT 50");
  } elseif (isset($_GET['edit'])) {
    $orderList = $connect->getData("SELECT * FROM orders WHERE last_update > '" . date("Y-m-d G:i:s", $_SESSION['last_update']) . "'");
  } else {
    $orderList = $connect->getData("SELECT * FROM orders ORDER BY id DESC");
  }
  $_SESSION["last_update"] = time();
  foreach ($orderList as $key => $elem) {
    $mainList["list"][] = [
      "id" => $elem["id"],
      "no_order_1c" => $elem["no_order_1c"],
      "payment_method" => $elem["payment_method"] != NULL ? $elem["payment_method"] : 0,
      "nick" => $elem["nick"],
      "full_name" => $elem["full_name"],
      "connection" =>  $elem["id_connection"],
      "city" => $elem['id_city'],
      "street" => $elem["street"],
      "house" => $elem["house"],
      "corpus" => $elem["corpus"],
      "entrance" => $elem["entrance"],
      "flat" => $elem["flat"],
      "number" => $elem["number"],
      "status" => $elem["id_status"],
      "note" => $elem["note"],
      "sum" => $elem["sum"],
      "weight" => $elem["weight"],
      "purchase" => $elem["purchase"],
      "square" => $elem["square"],
      "time" => $elem["time"],
      "last_update" => $elem["last_update"],
      "measuring_date" => $elem["measuring_date"],
      "measuring_time" => $elem["measuring_time"],
      "delivery_date" => $elem["delivery_date"],
      "delivery_time" => $elem["delivery_time"],
      "expectation" => $elem["expectation"],
      "productList" => [
        'linoleum' => [],
        'plinth' => [],
        'accessories' => [],
        'doorstep' => [],
        'related' => [],
      ]
    ];
  }
  foreach ($mainList["list"] as $key => $elem) {
    $sql = "SELECT * FROM product_list WHERE id_order = " . $elem['id'] . " Order by id_related,id_doorstep,id_accessories,id_plinth,id_linoleum DESC";
    $data = $connect->getData($sql);
    $sum = 0;
    $i = 1;
    foreach ($data as $element) {
      $list = [];
      if ($element['id_linoleum'] != null) {
        $list['key'] = $i++;
        $list['id'] = $element['id'];
        $list['id_linoleum'] = $element['id_linoleum'];

        $list['product'] = " " . $connect->getData("SELECT `name` FROM linoleum_maker WHERE id = (SELECT id_maker FROM linoleum WHERE id = " . $element['id_linoleum'] . ")")[0]['name'] . " " . $connect->getData("SELECT `name` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $element['id_linoleum'] . ")")[0]['name'] . " " . $connect->getData("SELECT name FROM linoleum WHERE id = " . $element['id_linoleum'])[0]['name'];

        $list['ei'] = "м<sup>2</sup>";

        $list["weight"] = $connect->getData("SELECT `weight` FROM linoleum_collection WHERE id = (SELECT id_collection FROM linoleum WHERE id = " . $element['id_linoleum'] . ")")[0]['weight'];
        $list["width"] = [
          "select" => $connect->getData("SELECT width FROM linoleum_width WHERE id = " . $element['id_width'])[0]['width'],
        ];
        foreach ($connect->getData("SELECT width FROM linoleum_width WHERE id_linoleum = " . $element['id_linoleum']) as $width) {
          $list["width"]['all'][] = $width['width'];
        }
        $list["len"] = $element["length"];
        $list["price"] = [
          "fact" => $element['final_price']
        ];
        $sum += $list["width"]['select'] * $list["len"];
        $collection = $connect->getData("SELECT id_collection FROM linoleum WHERE id = " . $element['id_linoleum'])[0]['id_collection'];
        $elData = $connect->getData("SELECT id FROM linoleum_width WHERE id_linoleum = " . $element['id_linoleum']);
        $sum_price = 0;
        foreach ($elData as $el) {
          $sum_price += $connect->getData("SELECT MAX(price) as price FROM linoleum_price WHERE id_linoleum_width =" . $el['id'])[0]['price'];
        }
        $num = $connect->query("SELECT id FROM linoleum_width WHERE id_linoleum = " . $element['id_linoleum'], "result")->num_rows;
        if ($num != 0) {
          $avg = $sum_price / $num;
        }
        $elData = 0;
        $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE id in (SELECT id_provider FROM linoleum_price WHERE id_linoleum_width in (SELECT id FROM linoleum_width Where id_linoleum = ' . $list['id_linoleum'] . "))");
        if (count($list['providers']) == 0) {
          $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE 1');
          foreach ($list["providers"] as $el) {
            foreach ($list['width']['all'] as $width) {
              $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id'], 'width' => $width];
            }
          }
        } else {
          $list["purchase_price_list"] = $connect->getData('SELECT price as purchase_price, id_provider, width FROM linoleum_price, linoleum_width WHERE linoleum_width.id = id_linoleum_width AND id_linoleum = ' . $list['id_linoleum']);
        }

        $list["provider"] = $element['id_provider'];
        if ($element['purchase_price'] == 0)
          foreach ($list["purchase_price_list"] as $width) {
            if ($width['width'] == $list["width"]['select']) {
              $list['purchase_price'] = $width['purchase_price'];
              break;
            }
          }

        else {
          $list["purchase_price"] = $element['purchase_price'];
        }
        $elData = $connect->getData("SELECT * FROM `linoleum_allowances` WHERE id_collection = $collection")[0];
        $list["price"]['rrc']['all'] = [
          'p0_10' => round($avg + ($avg * ($elData['0_10'] / 100)), 2),
          'p11_15' => round($avg + ($avg * ($elData['11_15'] / 100)), 2),
          'p16_20' => round($avg + ($avg * ($elData['16_20'] / 100)), 2),
          'p21_30' => round($avg + ($avg * ($elData['21_30'] / 100)), 2),
          'p31_40' => round($avg + ($avg * ($elData['31_40'] / 100)), 2),
          'p41_55' => round($avg + ($avg * ($elData['41_55'] / 100)), 2),
          'p56_70' => round($avg + ($avg * ($elData['56_70'] / 100)), 2),
        ];
        $mainList["list"][$key]['productList']['linoleum'][] = $list;
        continue;
      }
      if ($element['id_plinth'] != NULL) {
        $list['key'] = $i++;
        $list['id'] = $element['id'];
        $list['id_plinth'] = $element['id_plinth'];
        $list['product'] = "Плинтуса " . $connect->getData("SELECT name FROM plinth WHERE id = " . $element['id_plinth'])[0]['name'];
        $list['ei'] = "шт";
        $list['col_vo'] = $element['col-vo'];
        $list['price'] = $connect->getData("SELECT MAX(price) as price FROM plinth_price, plinth WHERE plinth.id_collection = plinth_price.id_plinth_collection and plinth.id = " . $element['id_plinth'])[0]['price'];
        if ($list['price'] == null) {
          $list['price'] = "0.00";
        }
        $list['weight'] = $connect->getData("SELECT `weight` FROM plinth_collection,plinth WHERE id_collection = plinth_collection.id and plinth.id = " . $element['id_plinth'])[0]['weight'];
        $list['final_price'] = $element['final_price'];
        $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE id in (SELECT id_provider FROM plinth_price WHERE id_plinth_collection in (SELECT id_collection FROM plinth WHERE id = ' . $element['id_plinth'] . '))');
        if (count($list['providers']) == 0) {
          $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE 1');
          foreach ($list["providers"] as $el) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id']];
          }
        } else {
          $list["purchase_price_list"] = $connect->getData('SELECT purchase_price, id_provider FROM plinth_price WHERE id_plinth_collection in (SELECT id_collection FROM plinth WHERE id = ' . $element['id_plinth'] . ')');
        }
        $list["provider"] = $element['id_provider'];
        if ($element['purchase_price'] == 0)
          $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
        else {
          $list["purchase_price"] = $element['purchase_price'];
        }
        $mainList["list"][$key]['productList']['plinth'][] = $list;
        continue;
      }
      if ($element['id_accessories'] != NULL) {
        $list['key'] = $i++;
        $list['id'] = $element['id'];
        $list['id_accessories'] = $element['id_accessories'];
        $list['product'] = $connect->getData("SELECT concat(plinth_accessories.name,' ',plinth.name) as name FROM plinth_accessories JOIN plinth ON id_plinth=plinth.id WHERE plinth_accessories.id = " . $element['id_accessories'])[0]['name'];
        $list['ei'] = "шт";
        $list['col_vo'] = $element['col-vo'];
        $list['price'] = $connect->getData("SELECT MAX(price) as price FROM plinth_accessories_price WHERE id_accessories = " . $element['id_accessories'])[0]['price'];
        if ($list['price'] == null) {
          $list['price'] = "0.00";
        }
        $list['final_price'] = $element['final_price'];
        $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE id in (SELECT id_provider FROM plinth_accessories_price WHERE id_accessories = ' . $element['id_accessories'] . ')');
        if (count($list['providers']) == 0) {
          $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE 1');
          foreach ($list["providers"] as $el) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id']];
          }
        } else {
          $list["purchase_price_list"] = $connect->getData('SELECT purchase_price, id_provider FROM plinth_accessories_price WHERE id_accessories = ' . $element['id_accessories']);
        }
        $list["provider"] = $element['id_provider'];
        if ($element['purchase_price'] == 0)
          $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
        else {
          $list["purchase_price"] = $element['purchase_price'];
        }
        $mainList["list"][$key]['productList']['accessories'][] = $list;
        continue;
      }
      if ($element['id_doorstep'] != NULL) {
        $list['key'] = $i++;
        $list['id'] = $element['id'];
        $list['id_doorstep'] = $element['id_doorstep'];
        $list['product'] = "Порог " . $connect->getData("SELECT concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color AND doorstep.id = " . $element['id_doorstep'])[0]['name'];
        $list['ei'] = "шт";
        $list['col_vo'] = $element['col-vo'];
        $list['price'] = $connect->getData("SELECT MAX(price) as price FROM doorstep_price WHERE id_doorstep = " . $element['id_doorstep'])[0]['price'];
        if ($list['price'] == null) {
          $list['price'] = "0.00";
        }
        $list['final_price'] = $element['final_price'];
        $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE id in (SELECT id_provider FROM doorstep_price WHERE id_doorstep = ' . $element['id_doorstep'] . ')');
        if (count($list['providers']) == 0) {
          $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE 1');
          foreach ($list["providers"] as $el) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id']];
          }
        } else {
          $list["purchase_price_list"] = $connect->getData('SELECT purchase_price, id_provider FROM doorstep_price WHERE id_doorstep = ' . $element['id_doorstep']);
        }
        $list["provider"] = $element['id_provider'];
        if ($element['purchase_price'] == 0)
          $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
        else {
          $list["purchase_price"] = $element['purchase_price'];
        }
        $mainList["list"][$key]['productList']['doorstep'][] = $list;
      }
      if ($element['id_related'] != NULL) {
        $list['key'] = $i++;
        $list['id'] = $element['id'];
        $list['id_related'] = $element['id_related'];
        $list['product'] = $connect->getData("SELECT name FROM related WHERE id = " . $element['id_related'])[0]['name'];
        $list['ei'] = $connect->getData("SELECT ei FROM related WHERE id = " . $element['id_related'])[0]['ei'];
        $list['col_vo'] = $element['col-vo'];
        $list['price'] = $connect->getData("SELECT MAX(price) as price FROM related_price WHERE id_related = " . $element['id_related'])[0]['price'];
        if ($list['price'] == null) {
          $list['price'] = "0.00";
        }
        $list['final_price'] = $element['final_price'];
        $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE id in (SELECT id_provider FROM related_price WHERE id_related = ' . $element['id_related'] . ')');
        if (count($list['providers']) == 0) {
          $list["providers"] = $connect->getData('SELECT id,short_name as name FROM provider WHERE 1');
          foreach ($list["providers"] as $el) {
            $list["purchase_price_list"][] = ['purchase_price' => '0.00', 'id_provider' => $el['id']];
          }
        } else {
          $list["purchase_price_list"] = $connect->getData('SELECT purchase_price, id_provider FROM related_price WHERE id_related = ' . $element['id_related']);
        }
        $list["provider"] = $element['id_provider'];
        if ($element['purchase_price'] == 0)
          $list['purchase_price'] = $list['purchase_price_list'][0]['purchase_price'];
        else {
          $list["purchase_price"] = $element['purchase_price'];
        }
        $mainList["list"][$key]['productList']['related'][] = $list;
        continue;
      }
    }
    $mainList["list"][$key]['lastKey'] = count($data);
    if ($sum > 0 && $sum <= 10) $rKey = "p0_10";
    else if ($sum <= 15) $rKey = "p11_15";
    else if ($sum <= 20) $rKey = "p16_20";
    else if ($sum <= 30) $rKey = "p21_30";
    else if ($sum <= 40) $rKey = "p31_40";
    else if ($sum <= 55) $rKey = "p41_55";
    else $rKey = "p56_70";
    foreach ($mainList["list"][$key]['productList']['linoleum'] as $k => $elementPrice) {
      $mainList["list"][$key]['productList']['linoleum'][$k]["price"]['rrc']['selected'] = $elementPrice["price"]['rrc']['all'][$rKey];
    }
  }

  echo json_encode($mainList, JSON_UNESCAPED_UNICODE);
}
