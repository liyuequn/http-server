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

$server = new \Liyuequn\Server($address, $port);

$requestHandler = new \Liyuequn\RequestHandler();
$responseHandler = new \Liyuequn\ResponseHandler();

$server->setRequestHandler($requestHandler);
$server->setResponseHandler($responseHandler);
$server->start();
