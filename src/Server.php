<?php
/**
 * Created by PhpStorm.
 * User: liyuequn
 * Date: 2019/5/15
 * Time: 下午3:34
 */

namespace Liyuequn;

class Server implements ServerInterface
{
    private $ip;
    private $port;
    /**
     * @var ContextHandler
     */
    private $contextParser;

    private $welcome;
    private $requestCallback;
    private $requestCallbackArgs;
    private $responseCallback;
    private $responseCallbackArgs;

    public function __construct($ip, $port)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->setWelcome();
    }

    public function setContextParser(ContextHandler $requestHandler)
    {
        $this->contextParser = $requestHandler;
    }

    public function setRequestCallback($callback,$args)
    {
        $this->requestCallback = $callback;
        $this->requestCallbackArgs = $args;

    }

    public function setResponseCallback($callback,$args)
    {
        $this->responseCallback = $callback;
        $this->responseCallbackArgs = $args;
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
                $context = socket_read($clientFd, 1024);
                $this->resolve2http($context);

                call_user_func_array($this->requestCallback,$this->requestCallbackArgs);

                socket_write($clientFd, call_user_func_array($this->responseCallback,$this->responseCallbackArgs));
                socket_close($clientFd);
            } catch (Exception $e) {
                echo $e->getMessage();
                $this->error("READ FAILED:");
            }
        } while (true);
    }

    /**
     * @param $request
     * <method> <protocl>
     * <host>
     */
    public function resolve2http($request)
    {
        file_put_contents('http.log', $request, FILE_APPEND);
        $this->contextParser->parse($request);
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
