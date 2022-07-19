<?php
$request_body = file_get_contents('php://input');
$data_page = json_decode($request_body, true);
if (isset($data_page['type'])) {
    include_once('./../../backend/librares/connect.php');
    switch ($data_page['type']) {
        case "allowances": {
                $queryData = getData("SELECT * FROM linoleum_allowances WHERE id_collection = ". $data_page['id'])[0];
                $data[] = [
                    "tag_name" => "collection",
                    "tag" => "input",
                    "type" => "hidden",
                    "value" => $data_page['id']
                ];
                foreach ($queryData as $key => $elem) {
                    if($key != "id_collection")
                    $data[] = [
                        "name" => $key ,
                        "tag_name" => $key,
                        "value" => $elem,
                        "tag" => "input",
                        "type" => "text",
                    ];
                }
                $data[] =
                    [
                        "tag" => "button",
                        "tag_name" => "linoleum_allowances"
                    ];
                break;
            }
        case "price": {
                $dataWidth = getData("SELECT DISTINCT width, price FROM linoleum_width WHERE id_collection = " . $data_page['id']);
                $data[] = [
                    "tag_name" => "collection",
                    "tag" => "input",
                    "type" => "hidden",
                    "value" => $data_page['id']
                ];
                foreach ($dataWidth as $width) {
                    $data[] = [
                        "name" => "Ширина " . $width['width'],
                        "tag_name" => $width['width'],
                        "value" => $width['price'],
                        "tag" => "input",
                        "type" => "text",
                    ];
                }
                $data[] =
                    [
                        "tag" => "button",
                        "tag_name" => "price"
                    ];
                break;
            }
        case "recommended": {
                $data = [
                    [
                        "name" => "Название",
                        "tag_name" => "id_linoleum",
                        "tag" => "select",
                        "multiple" => false,
                        "options" => getData("select linoleum.id, concat(collection.name,' ',linoleum.name)as name from linoleum, linoleum_collection as collection WHERE id_collection = collection.id")
                    ],
                    [
                        "name" => "Название",
                        "tag_name" => "id_plinth",
                        "tag" => "select",
                        "multiple" => false,
                        "options" => getData("select plinth.id, concat(collection.name,' ',plinth.name)as name from plinth, plinth_collection as collection WHERE id_collection = collection.id;")
                    ],
                    [
                        "tag" => "button",
                        "tag_name" => $data_page['type']
                    ]
                ];
                break;
            }
        case "linoleum": {
                $data = [
                    [
                        "name" => "Название",
                        "tag_name" => "name",
                        "tag" => "input",
                        "type" => "text"
                    ],
                    [
                        "name" => "Производитель",
                        "tag_name" => "id_maker",
                        "tag" => "select",
                        "multiple" => false,
                        "options" => getData("select * from linoleum_maker")
                    ],
                    [
                        "name" => "Коллекция",
                        "tag_name" => "id_collection",
                        "tag" => "select",
                        "multiple" => false,
                        "options" => getData("select id,name from linoleum_collection")
                    ],
                    [
                        "name" => "Ширины",
                        "tag_name" => "width[]",
                        "tag" => "select",
                        "multiple" => true,
                        "options" => [
                            ["id" => 1.5, "name" => 1.5],
                            ["id" => 2, "name" => 2],
                            ["id" => 2.5, "name" => 2.5],
                            ["id" => 3, "name" => 3],
                            ["id" => 3.1, "name" => 3.1],
                            ["id" => 3.2, "name" => 3.2],
                            ["id" => 3.5, "name" => 3.5],
                            ["id" => 4, "name" => 4],
                            ["id" => 5, "name" => 5],
                        ]
                    ],
                    [
                        "tag" => "input",
                        "type" => "file",
                        "multiple" => true
                    ],
                    [
                        "tag" => "button",
                        "tag_name" => $data_page['type']
                    ]
                ];
                break;
            }
        case "plinth": {
                $data = [
                    [
                        "name" => "Название",
                        "tag_name" => "name",
                        "tag" => "input",
                        "type" => "text"
                    ],
                    [
                        "name" => "Производитель",
                        "tag_name" => "id_maker",
                        "tag" => "select",
                        "multiple" => false,
                        "options" => getData("select * from plinth_maker")
                    ],
                    [
                        "name" => "Коллекция",
                        "tag_name" => "id_collection",
                        "tag" => "select",
                        "multiple" => false,
                        "options" => getData("select id,name from plinth_collection")
                    ],
                    [
                        "tag" => "input",
                        "type" => "file",
                        "multiple" => false
                    ],
                    [
                        "tag" => "button",
                        "tag_name" => $data_page['type']
                    ]
                ];
                break;
            }
        case 'collection': {
                if ($data_page['product'] == "linoleum") {
                    $data = [
                        [
                            "name" => "Название",
                            "tag_name" => "name",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "Производитель",
                            "tag_name" => "id_maker",
                            "tag" => "select",
                            "multiple" => false,
                            "options" => getData("select id,name from linoleum_maker")
                        ],
                        [
                            "name" => "Предназанчение",
                            "tag_name" => "id_destination",
                            "tag" => "select",
                            "multiple" => false,
                            "options" => getData("select id,name from linoleum_destination")
                        ],
                        [
                            "name" => "Класс",
                            "tag_name" => "id_class",
                            "tag" => "select",
                            "multiple" => false,
                            "options" => getData("select id,name from linoleum_class")
                        ],
                        [
                            "name" => "Основа",
                            "tag_name" => "id_base",
                            "tag" => "select",
                            "multiple" => false,
                            "options" => getData("select id,name from linoleum_base")
                        ],
                        [
                            "name" => "Тип",
                            "tag_name" => "id_type",
                            "tag" => "select",
                            "multiple" => false,
                            "options" => getData("select id,name from linoleum_type")
                        ],
                        [
                            "name" => "Толщина общая",
                            "tag_name" => "total_thickness",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "Толщина защитного слоя",
                            "tag_name" => "protective",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "Вес",
                            "tag_name" => "weight",
                            "tag" => "input",
                            "type" => "text"
                        ]

                    ];
                } else if ($data_page['product'] == "plinth") {
                    $data = [
                        [
                            "name" => "Название",
                            "tag_name" => "name",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "Производитель",
                            "tag_name" => "id_maker",
                            "tag" => "select",
                            "multiple" => false,
                            "options" => getData("select id,name from plinth_maker")
                        ],
                        [
                            "name" => "Ширина",
                            "tag_name" => "width",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "Высота",
                            "tag_name" => "heigth",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "Длинна",
                            "tag_name" => "length",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "Вес",
                            "tag_name" => "weight",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "РРЦ",
                            "tag_name" => "price",
                            "tag" => "input",
                            "type" => "text"
                        ],
                        [
                            "name" => "Закупочная цена",
                            "tag_name" => "purchase_price",
                            "tag" => "input",
                            "type" => "text"
                        ]
                    ];
                }
                $data[] = [
                    "tag" => "button",
                    "tag_name" => $data_page['product'] . "_" . $data_page['type']
                ];
                break;
            }
            case 'accessories': {
                $data = [
                    [
                        "name" => "Название",
                        "tag_name" => "name",
                        "tag" => "select",
                        "multiple" => false,
                        "options"=>[
                            ["id"=>"Стык","name"=>"Стык"],
                            ["id"=>"Торцевая левая","name"=>"Торцевая левая"],
                            ["id"=>"Торцевая правая","name"=>"Торцевая правая"],
                            ["id"=>"Торцевая пара","name"=>"Торцевая пара"],
                            ["id"=>"Угол внутренний","name"=>"Угол внутренний"],
                            ["id"=>"Угол наружный","name"=>"Угол наружный"],
                        ]
                    ],
                    [
                        "name" => "РРЦ",
                        "tag_name" => "price",
                        "tag" => "input",
                        "type" => "text"
                    ],
                    [
                        "name" => "Закупочная цена",
                        "tag_name" => "purchase_price",
                        "tag" => "input",
                        "type" => "text"
                    ],
                    [
                        "name" => "Плинтус",
                        "tag_name" => "id_plinth",
                        "tag" => "select",
                        "multiple" => false,
                        "options" => getData("select plinth.id, concat(collection.name,' ',plinth.name)as name from plinth, plinth_collection as collection WHERE id_collection = collection.id;")
                    ],
                    [
                        "tag" => "button",
                        "tag_name" => $data_page['product'] . "_" . $data_page['type']
                    ]
                ];
                break;
            }
        default: {
                $data = [
                    [
                        "name" => "Название",
                        "tag_name" => "name",
                        "tag" => "input",
                        "type" => "text"
                    ],
                    [
                        "tag" => "button",
                        "tag_name" => $data_page['product'] . "_" . $data_page['type']
                    ]
                ];
            }
    }
    echo  json_encode($data, JSON_UNESCAPED_UNICODE);
}
