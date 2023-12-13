<?php
header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once("../../librares/connect.php");
if (isset($_POST['table'])) {
    switch ($_POST['table']) {
        case "linoleum": {
                $data = [];
                if (isset($_POST['maker'])) {
                    $data['collections'] = $connect->getData("SELECT id, name FROM linoleum_collection WHERE id_maker = " . $_POST['maker']);
                } else {
                    $data['collections'] = $connect->getData("SELECT `id`, `name` FROM linoleum_collection");
                }
                if (isset($_POST['collection'])) {
                    $data['linoleums'] = $connect->getData("SELECT id, name FROM linoleum WHERE id_collection = " . $_POST['collection']);
                } else {
                    $data['linoleums'] = $connect->getData("SELECT `id`, `name` FROM linoleum");
                }
                if (isset($_POST['id'])) {
                    $width = $connect->getData("SELECT id, width as name FROM linoleum_width WHERE id_linoleum = " . $_POST['id'] . " ORDER BY `linoleum_width`.`width` ASC");
                    if (isset($_POST['provider'])) {
                        foreach ($width as $key => $item) {
                            $price = $connect->getData("SELECT price FROM linoleum_price WHERE id_linoleum_width = " . $item['id'] . " AND id_provider = " . $_POST['provider']);
                            if (count($price) != 0) {
                                $width[$key]['selected'] = 1;
                                $width[$key]['value'] = $price[0]['price'];
                            }
                        }
                    }
                    $data['width'] = $width;
                } else {
                    $data['width'] = [];
                }
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            }
        case 'plinth': {
                if (isset($_POST['maker'])) {
                    $data['collections'] = $connect->getData("SELECT id, name FROM plinth_collection WHERE id_maker = " . $_POST['maker']);
                } else {
                    $data['collections'] = $connect->getData("SELECT `id`, `name` FROM plinth_collection");
                }
                if (isset($_POST['collection'])) {
                    if (isset($_POST['provider'])) {
                        $price = $connect->getData("SELECT price as rrc, purchase_price as opt FROM plinth_price WHERE id_plinth_collection = " . $_POST['collection'] . " AND id_provider = " . $_POST['provider']);
                        if (count($price) != 0) {
                            $data['opt'] = $price[0]['opt'];
                            $data['rrc'] = $price[0]['rrc'];
                        } else {
                            $data['opt'] = '';
                            $data['rrc'] = '';
                        }
                    }
                }
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            }
        case 'accessories': {
                if (isset($_POST['maker'])) {
                    $data['collections'] = $connect->getData("SELECT id, name FROM plinth_collection WHERE id_maker = " . $_POST['maker']);
                } else {
                    $data['collections'] = $connect->getData("SELECT `id`, `name` FROM plinth_collection");
                }
                if (isset($_POST['collection'])) {
                    $data['plinths'] = $connect->getData("SELECT id, name FROM plinth WHERE id_collection = " . $_POST['collection']);
                } else {
                    $data['plinths'] = $connect->getData("SELECT `id`, `name` FROM plinth");
                }
                if (isset($_POST['plinth'])) {
                    $accessories = $connect->getData("SELECT id, name FROM plinth_accessories WHERE id_plinth = " . $_POST['plinth']);
                    if (isset($_POST['provider'])) {
                        foreach ($accessories as $key => $item) {
                            $price = $connect->getData("SELECT price as rrc, purchase_price as opt FROM plinth_accessories_price WHERE id_accessories = " . $item['id'] . " AND id_provider = " . $_POST['provider']);
                            if (count($price) != 0) {
                                $accessories[$key]['opt'] = $price[0]['opt'];
                                $accessories[$key]['rrc'] = $price[0]['rrc'];
                            } else {
                                $accessories[$key]['opt'] = 0;
                                $accessories[$key]['rrc'] = 0;
                            }
                        }
                    }
                    $data['accessories'] = $accessories;
                } else {
                    $data['accessories'] = [];
                }
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            }
        case 'doorstep': {
                if (isset($_POST['maker'])) {
                    $data['doorsteps'] = $connect->getData("SELECT doorstep.id as id, concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color AND id_maker = " . $_POST['maker'] . " ORDER BY doorstep_color.name ASC");
                } else {
                    $data['doorsteps'] = $connect->getData("SELECT doorstep.id as id, concat(doorstep_maker.name,' ',doorstep_color.name,' (',width,'*',length,'*',depth,')') as `name` FROM doorstep, doorstep_size, doorstep_maker, doorstep_color WHERE doorstep_size.id = id_size AND doorstep_maker.id = id_maker AND doorstep_color.id = id_color ORDER BY doorstep_color.name ASC");
                }
                if (isset($_POST['doorstep'])) {
                    if (isset($_POST['provider'])) {
                        $price = $connect->getData("SELECT price as rrc, purchase_price as opt FROM doorstep_price WHERE id_doorstep = " . $_POST['doorstep'] . " AND id_provider = " . $_POST['provider']);
                        if (count($price) != 0) {
                            $data['opt'] = $price[0]['opt'];
                            $data['rrc'] = $price[0]['rrc'];
                        } else {
                            $data['opt'] = '';
                            $data['rrc'] = '';
                        }
                    }
                }
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
                break;
            }
        case "related": {
                if (isset($_POST['related'])) {
                    if (isset($_POST['provider'])) {
                        $price = $connect->getData("SELECT price as rrc, purchase_price as opt FROM related_price WHERE id_related = " . $_POST['related'] . " AND id_provider = " . $_POST['provider']);
                        if (count($price) != 0) {
                            $data['opt'] = $price[0]['opt'];
                            $data['rrc'] = $price[0]['rrc'];
                        } else {
                            $data['opt'] = '';
                            $data['rrc'] = '';
                        }
                    }
                }
                echo  json_encode($data, JSON_UNESCAPED_UNICODE);
            }
    }
}
