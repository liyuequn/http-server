<?php
/**
 * Created by PhpStorm.
 * User: liyuequn
 * Date: 2018/8/23
 * Time: 下午3:52
 */

$content = '<h1 style="
text-align: center;
color:aqua;
margin-top: 200px;
font-size:81px;
font-weight: bold;
background: grey;
">
Hello PhpWebServer   !!</h1>';
$address = '0.0.0.0';
$port = 8888;
$length = 1024;

include "./ResponseHandler.php";

$http = new ResponseHandler();

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//can reuse port
socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);

socket_bind($sock,$address,$port);

socket_listen($sock,128);

$clients [] = $sock;

while (true)
{
    $reads = $clients;
    if(socket_select($reads,$writes,$except,NULL) < 1) {
        continue;
    }

    if(in_array($sock,$reads))
    {
        $writes[] = $sockAccepted = socket_accept($sock);
        $clients[] = $sockAccepted;
        $key = array_search($sock,$reads);
        unset($reads[$key]);

    }

    foreach ($reads as $read)
    {
        $data = @socket_read($read,$length);

        if($data === false)
        {
            unset($clients[array_search($read,$clients)]);
            unset($reads[array_search($read,$reads)]);
            continue;
        }
        $data = $http->response($content);
        socket_write($read,$data);
    }

}

socket_close($sock);
