<?php
/**
 * Created by PhpStorm.
 * User: liyuequn
 * Date: 2019/5/15
 * Time: 下午6:41
 */
namespace Liyuequn;

interface ServerInterface
{
    public function setRequestHandler(RequestHandler $requestHandler);

    public function setResponseHandler(ResponseHandler $responseHandler);
}
