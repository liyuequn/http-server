<?php
/**
 * Created by PhpStorm.
 * User: liyuequn
 * Date: 2019/5/15
 * Time: 下午2:31
 */

spl_autoload_register(function ($class) {
    require __DIR__ . "/" . str_replace('\\', '/', $class) . '.php';
});


$address = '0.0.0.0';
$port = 8889;

$server = new Server($address, $port);
$server->start();




