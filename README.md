### Redis-Lock 分布式锁

- 使用lua原子操作，更安全
- 支持redis集群
- 支持自动续期

### Install

```
composer require purelightme/redis-lock
```

### Test

```
php test/run.php
```

### Demo

```php
$config = [
    'host' => 'redis',
    'name' => 'default',
    'ttl' => 60,
    'interval' => 5,
];

try{
    $res = SequenceTask::execute($config,function (){
        //fake long time logic...
        sleep(20);
        return 'job execute success';
    });
}catch (InternalException $exception){
    $res = $exception->getMessage();
}catch (Throwable $exception){
    $res = $exception->getMessage();
}

var_dump($res);
```

### Requirement

- pcntl 扩展

### 使用场景

- 订单超时自动取消
- 定时生成系统报表数据
- crontab任务【可避免任务重叠导致的问题】
- ......
