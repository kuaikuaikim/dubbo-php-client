# dubbo-php-client
the dubbo php client

[Dubbo](https://github.com/alibaba/dubbo) is a distributed service framework empowers applications with service import/export capability with high performance RPC.  

This is only dubbo php clinet implementation. It's only support jsonRPC now.  
you can see the example for the [dubbo-jsonRPC-demo](https://github.com/quickj/dubbo_jsonrpc_demo) which i write before.  
Notice: you must start dubbo and zookeeper,register prividers first.  

###Installation  
```bash
composer require quickj/dubbo-php-client
```  

###Usage
```php
use \dubbo\dubboClient;
// options for register consumer
// 注册消费者配置
$options= array(
    "registry_address" => "127.0.0.1:2181"
);
$dubboCli = new dubboClient($options);
$HelloService = $dubboCli->getService("com.dubbo.demo.HelloService","1.0.0",null);
$ret = $HelloService->hello("dubbo php client");
echo $ret;
```
