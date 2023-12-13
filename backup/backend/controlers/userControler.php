<?php
include("./librares/connect.php");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



class User
{
    public $connect;

    function __construct($classConnect)
    {
        $this->connect = $classConnect;
    }

    public function authorization($dataUser)
    {
        $result = $this->connect->query("SELECT `id`, `name`,`password`, `opened_tabs` FROM `users` WHERE `login`='" . $dataUser['login'] . "'", "result");
        if ($result->num_rows === 1) {

            $data = $result->fetch_assoc();
            if (password_verify($dataUser['password'], $data['password'])) {
                $data['admin'] = 0;
                unset($data['password']);
                $right = $this->connect->getData("SELECT `id_right` as `right`, id_category FROM `rights_users`,`rights` WHERE `rights`.`id` = `id_right` AND `id_user` = " . $data['id'] . ";");
                $data['opened_tabs'] = json_decode($data['opened_tabs'], JSON_UNESCAPED_UNICODE);
                if (count($right) != 0) {
                    foreach ($right as $elem) {
                        if ($elem['id_category'] == 1) {
                            $data['admin'] = 1;
                        }
                        $data['rights'][] = $elem['right'];
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        $_SESSION['user'] = $data;
                    }
                } else {
                    http_response_code(203);
                    $data = "Пользователь зарегестрирован, но не имеет доступа";
                }
            } else {
                http_response_code(203);
                $data = "Неверный логин или пароль";
            }
        } else {
            http_response_code(203);
            $data = "Неверный логин или пароль";
        }

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function updateTabs($tabs)
    {
        $this->connect->query("UPDATE users SET opened_tabs = '" . json_encode($tabs, JSON_UNESCAPED_UNICODE) . "' WHERE id = " . $_SESSION['user']['id'] . ";");
    }

    public function exit()
    {
        $this->connect->query("UPDATE users SET opened_tabs = '" . json_encode([], JSON_UNESCAPED_UNICODE) . "' WHERE id = " . $_SESSION['user']['id'] . ";");
        session_destroy();
    }
}

$user = new User($connect);
