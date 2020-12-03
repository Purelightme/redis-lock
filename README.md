### Redis-Lock 分布式锁

- 使用lua原子操作，更安全
- 支持redis集群
- 支持自动续期(多进程实现，类似 java redisson 的 watch dog)

### Install

```
composer require purelightme/redis-lock
```

### Test

```
php test/run.php
```

### Demo

> 若不想要自动续期功能，可直接使用 RedisLock 类手动控制加锁释放锁

```php
$config = [
    'host' => 'redis',
    'name' => 'default',
    'ttl' => 60,//原子锁ttl
    'interval' => 5,//子进程自动续期周期
    //...其他predis支持的参数
];

try{
    $res = SequenceTask::execute($config,function (){
        //fake long time logic...
        sleep(20);
        return 'job execute success';
    });
}catch (InternalException $exception){
    //一般情况下无需关注
    $res = $exception->getMessage();
}catch (Throwable $exception){
    //任务执行中本身的异常，需要关注
    $res = $exception->getMessage();
}

var_dump($res);
```

### Requirement

- pcntl 扩展
- posix 扩展(可选)

### 使用场景

- 订单超时自动取消
- 定时生成系统报表数据
- crontab任务【可100%避免任务重叠导致的问题】
- ......
