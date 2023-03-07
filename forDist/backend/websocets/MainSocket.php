<?php
date_default_timezone_set('Europe/Moscow');

use Workerman\Worker;

require_once "./vendor/autoload.php";



$wsWorker = new Worker('websocket://server:81/');
$wsWorker->count = 5;

$wsWorker->onConnect = function ($connect) use ($wsWorker) {
    echo "connected " . count($wsWorker->connections) . " users\n";
};

$wsWorker->onMessage = function ($connect, $request) use ($wsWorker) {
    $request = json_decode($request, true);
    switch ($request['type']) {
        case 'application':
            foreach ($wsWorker->connections as $user) {
                $user->send(json_encode($request['data'], JSON_UNESCAPED_UNICODE));
            }
            break;
    }
};

$wsWorker->onClose = function ($connect) use ($wsWorker) {
    echo "connected " . count($wsWorker->connections) - 1
        . " users\n";
};

Worker::runAll();
