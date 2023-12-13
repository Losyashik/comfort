<?php
// header("Content-Type: aplication/json");
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Credentials:true");
include_once('./../librares/connect.php');
$data = [];
if (isset($_POST["type_list"])) {
    if (isset($_POST['sortList'])) {
        $data = json_decode($_POST['sortList'], true);
        if ($_POST['type_list'] == "linoleum" or $_POST['type_list'] == "plinth") {
            $type = $_POST['type_list'];
            foreach ($data as $key => $elem) {
                if ($elem["value"] == "maker") {
                    $checked_element = [];
                    foreach ($elem['list'] as $item) {
                        if ($item['checked'] == 1) {
                            $checked_element[] = $item['id'];
                        }
                    }
                }

                if ($elem["value"] == "collection") {
                    if (count($checked_element) > 0) {
                        $query = "SELECT id, name, false as checked FROM " . $type . "_collection WHERE id_maker in(";
                        foreach ($checked_element as $item) {
                            $query .= "'$item',";
                        }
                        $query[strlen($query) - 1] = ')';
                    } else {
                        $query = "SELECT id,name , false as checked FROM " . $type . "_collection";
                    }
                    $list = $connect->getData($query);
                    foreach ($elem['list'] as $item) {
                        if ($item['checked'] == 1) {
                            $item["checked"] = false;
                            $list[array_search($item, $list)]['checked'] = true;
                        }
                    }
                    $data[$key]['list'] = $list;
                }
            }
        }
    } else if ($_POST['type_list'] == "linoleum") {
        $class = ' ';
        $data = [
            [
                "openList" => false,
                'value' => "maker",
                "name" => "Производитель",
                "list" => $connect->getData("SELECT id,name , 'false' as checked FROM linoleum_maker"),
            ],
            [
                "openList" => false,
                "name" => "Коллекции",
                "value" => "collection",
                "list" => $connect->getData("SELECT id,name, id_maker, 'false' as checked FROM linoleum_collection"),
            ],
            [
                "openList" => false,
                "name" => "Класс применения",
                "value" => "class",
                "list" => $connect->getData("SELECT id,name , 'false' as checked FROM linoleum_class"),
            ],
            [
                "openList" => false,
                "name" => "Толщина покрытия общая",
                "value" => "total_thickness",
                "list" => $connect->getData("SELECT distinct total_thickness as id, total_thickness as name , 'false' as checked FROM linoleum_collection ORDER BY total_thickness ASC"),
            ],
            [
                "openList" => false,
                "name" => "Тип линолиума",
                "value" => "type",
                "list" => $connect->getData("SELECT id,name , 'false' as checked FROM linoleum_type"),
            ],
            [
                "openList" => false,
                "name" => "Толщина защитного слоя",
                "value" => "protective",
                "list" => $connect->getData("SELECT distinct protective as id, protective as name , 'false' as checked FROM linoleum_collection ORDER BY protective ASC"),
            ],
            [
                "openList" => false,
                "name" => "Основа линолиума",
                "value" => "base",
                "list" => $connect->getData("SELECT id,name , 'false' as checked FROM linoleum_base"),
            ],
            [
                "openList" => false,
                "name" => "Предназачение линолиума",
                "value" => "destination",
                "list" => $connect->getData("SELECT id,name , 'false' as checked FROM linoleum_destination"),
            ],
            [
                "openList" => false,
                "name" => "Ширина рулона",
                "value" => "width",
                "list" => $connect->getData("SELECT distinct width as id, width as name , 'false' as checked FROM linoleum_width ORDER BY width ASC"),
            ]
        ];
    } else if ($_POST['type_list'] == "plinth") {
        $data = [
            [
                "openList" => false,
                'value' => "maker",
                "name" => "Производитель",
                "list" => $connect->getData("SELECT id,name , 'false' as checked FROM plinth_maker"),
            ],
            [
                "openList" => false,
                "name" => "Коллекции",
                "value" => "collection",
                "list" => $connect->getData("SELECT id,name , id_maker, 'false' as checked FROM plinth_collection"),
            ],
            [
                "openList" => false,
                "name" => "Высота",
                "value" => "heigth",
                "list" => $connect->getData("SELECT DISTINCT heigth as name, heigth as id , 'false' as checked FROM plinth_collection"),
            ]
        ];
    } else if ($_POST['type_list'] == "doorstep") {
        $data = [
            [
                "openList" => false,
                'value' => "maker",
                "name" => "Производитель",
                "list" => $connect->getData("SELECT id,name , 'false' as checked FROM doorstep_maker"),
            ],
            [
                "openList" => false,
                'value' => "color",
                "name" => "Цвет",
                "list" => $connect->getData("SELECT id,name , 'false' as checked FROM doorstep_color"),
            ],
            [
                "openList" => false,
                'value' => "size",
                "name" => "Размер",
                "list" => $connect->getData("SELECT id, concat(width,'*',length,'*',depth) as name , 'false' as checked FROM doorstep_size"),
            ]

        ];
    } elseif ($_POST['type_list'] == "related") {
        $data = [];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
