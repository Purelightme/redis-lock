<?php
/**
 * Created by
 * Author purelight
 * Date 2020-11-28
 * Time 00:43
 */


namespace Purelightme\RedisLock;


use Predis\Client;
use Predis\Response\Status;

/**
 * Redis分布式锁
 *
 * Class RedisLock
 */
class RedisLock
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string key
     */
    protected $name = 'default';

    /**
     * @var string requestId
     */
    protected $requestId;

    /**
     * @var int expire seconds
     */
    protected $ttl = 60;

    public function __construct(array $params)
    {
        if (isset($params['name'])) {
            $this->name = $params['name'];
        }
        if (isset($params['ttl'])) {
            $this->ttl = $params['ttl'];
        }
        $this->requestId = uniqid($this->name, true);
        unset($params['name'],$params['ttl']);
        $this->client = new Client($params);
    }

    /**
     * Attempt to acquire the lock
     *
     * @return bool
     */
    public function acquire()
    {
        $res = $this->client->set($this->name, $this->requestId, 'EX', $this->ttl, 'NX');
        if ($res instanceof Status){
            return $res->getPayload() === 'OK';
        }
        return false;
    }

    /**
     * Reset the lock ttl
     *
     * @return bool
     */
    public function keepAlive()
    {
        $lua = "if redis.call('get', KEYS[1]) == ARGV[1] then return redis.call('expire', KEYS[1], " .
            $this->ttl . ") else return 0 end";
        return $this->client->eval($lua, 1, $this->name, $this->requestId) === 1;
    }

    /**
     * Release the lock
     *
     * @return bool
     */
    public function release()
    {
        $lua = "if redis.call('get', KEYS[1]) == ARGV[1] then return redis.call('del', KEYS[1]) else return 0 end";
        return $this->client->eval($lua, 1, $this->name, $this->requestId) === 1;
    }

    public function __toString()
    {
        return $this->name.':'.$this->requestId.'['.$this->ttl.']';
    }
}
