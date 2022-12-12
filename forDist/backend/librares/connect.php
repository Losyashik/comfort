<?php
$driver = new mysqli_driver();
$driver->report_mode = MYSQLI_REPORT_OFF;
date_default_timezone_set('Europe/Moscow');
class connectDB
{

    public $link;

    public function __construct()
    {
        $this->link  =  mysqli_connect('', 'root', '', 'sk_komfort');
    }
    public function getData(string $query)
    {
        $this->link;
        $result = $this->link->query($query);
        if (!$result) {
            http_response_code(400);
            $data['text'] = "Ошибка SQL: " . $this->link->error . " Запрос: " . $query;
            $data['error'] = true;
            $data['active'] = true;
            echo  json_encode($data, JSON_UNESCAPED_UNICODE);
            $this->link->close();
            exit();
        }
        if ($result) {
            for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);
            return $data;
        }
    }
    public function query(string $query, string $ret = "link")
    {
        $this->link;
        if (!$result = $this->link->query($query)) {
            http_response_code(400);
            $data['text'] = "Ошибка SQL: " . $this->link->error . " Запрос: " . $query;
            $data['error'] = true;
            $data['active'] = true;
            echo  json_encode($data, JSON_UNESCAPED_UNICODE);
            exit();
        }
        if ($ret == 'link') {
            return $this->link;
        } else if ($ret == 'result') {
            return $result;
        }
    }
}
function getArrFiles(array $files)
{
    $images = [];
    foreach ($files as $key => $value) {
        for ($i = 0; $i < count($value); $i++) {
            $images[$i][$key] = $value[$i];
        }
    }
    return $images;
}
function arrayLog($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
function translite(string $str)
{
    $converter = array(
        'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
        'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
        'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
        'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
        'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
        'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
        'э' => 'e',    'ю' => 'yu',   'я' => 'ya',    " " => "_",

        'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
        'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
        'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
        'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
        'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
        'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
        'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
    );

    $str = strtr($str, $converter);
    return $str;
}
$connect = new connectDB();
