<?php
/**
 * Created by PhpStorm.
 * User: liyuequn
 * Date: 2019/5/15
 * Time: 下午2:31
 */

require "vendor/autoload.php";


$address = '0.0.0.0';
$port = 8889;

function response($hello)
{
    $responseHandler = new \Liyuequn\ResponseHandler();
    return $responseHandler->response($hello);
}

function request($world)
{
    return $world;
}

$server = new \Liyuequn\Server($address, $port);

$contextHandler = new \Liyuequn\ContextHandler();

$server->setRequestCallback('request',['hello']);
$server->setResponseCallback('response',['world']);

$server->setContextParser($contextHandler);
$server->start();
