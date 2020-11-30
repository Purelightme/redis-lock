<?php
/**
 * Created by
 * Author purelight
 * Date 2020-11-28
 * Time 10:52
 */


namespace Purelightme\RedisLock\Exception;


use Throwable;

class InternalException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
