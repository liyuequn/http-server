<?php
/**
 * Created by PhpStorm.
 * User: liyuequn
 * Date: 2018/8/23
 * Time: 上午11:57
 */

class ResponseHandler
{
    Const STATUS = [
        '200'=>'ok',

        '301'=>'Moved Permanently',
        '302'=>'Found',

        '400'=>'Bad Request',
        '401'=>'Unauthorized',
        '403'=>'Forbidden',
        '500'=>'Internal Server Error',
        '502'=>'Bad GateWay'
    ];

    Const OPTIONS = [
        "Server" => "phpHttpServer",
        "Content-Type" => "text/html; charset=UTF-8",
    ];



    protected $header;

    protected $options;

    protected $status;

    protected $content;

    public function __construct($status = 200)
    {

        $this->header = "HTTP/1.1 {$status} ".self::STATUS[$status]." \r\n";
        $this->options = self::OPTIONS;
    }

    public function setHeader(string $key, string $value)
    {
        $this->options[$key] = $value;
    }

    public function response(string $content,$status = 200)
    {
        $text = "HTTP/1.1 {$status} ".self::STATUS[$status]."\r\n";

        foreach ($this->options as $key => $option)
        {
            $text .= $key .": ".$option."\r\n";
        }
        $text .= "Content-Length: ".strlen($content)."\r\n";

        $this->header = $text;

        $this->content = $content;

        return $this->header."\r\n".$this->content;

    }
}