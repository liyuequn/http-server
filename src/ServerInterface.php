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
    public function setContextParser(ContextHandler $requestHandler);

    public function setResponseCallback($callback,$args);

    public function setRequestCallback($callback,$args);
}
