<?php
/**
 * Created by PhpStorm.
 * User: liyuequn
 * Date: 2019/5/15
 * Time: 下午3:34
 */

class Server implements ServerInterface
{
    private $ip;
    private $port;

    private $requestHandler;
    private $responseHandler;
    private $welcome;

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->setWelcome();
    }

    public function setRequestHandler(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    public function setResponseHandler(ResponseHandler $responseHandler)
    {
        $this->responseHandler = $responseHandler;
    }


    public function start()
    {
        $fd = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if ($fd < 0) {
            $this->error('Error');
        }

        if (socket_bind($fd, $this->ip, $this->port) < 0) {
            $this->error("Bind FAILED:");
        }

        if (socket_listen($fd) < 0) {
            $this->error('LISTEN FAILED');
        }

        echo $this->ip . ":" . $this->port . "\tserver start\n";

        do {
            $clientFd = null;
            try {
                $clientFd = socket_accept($fd);
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->error("ACCEPT FAILED");
            }

            try {
                $request = socket_read($clientFd, 1024);
                $this->requestHandler($request);

                $response = $this->response();

                socket_write($clientFd, $response);
                socket_close($clientFd);
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->error("READ FAILED:");
            }
        } while (true);
    }

    public function requestHandler($request)
    {
        $this->requestHandler->parseHttp($request);
    }

    public function response()
    {
        $content = $_GET['id'] ?? $this->welcome;
        $http = new ResponseHandler();
        $response = $http->response($content);
        return $response;
    }

    public function error($type)
    {
        echo $type . socket_strerror(socket_last_error()) . "\n";
        exit;
    }

    public function setWelcome()
    {
        $this->welcome = '<h1 style="
text-align: center;
color:aqua;
margin-top: 200px;
font-size:81px;
font-weight: bold;
background: grey;
">
欢迎使用 PhpWebServer   !!</h1>';
    }
}
