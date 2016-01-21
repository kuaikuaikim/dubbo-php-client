# dubbo-php-client
the dubbo php client

[Dubbo](https://github.com/alibaba/dubbo) is a distributed service framework empowers applications with service import/export capability with high performance RPC.  

This is only dubbo php clinet implementation. It's only support jsonRPC now.  
you can see the example for the [dubbo-jsonRPC-demo](https://github.com/quickj/dubbo_jsonrpc_demo) which i write before.  
Notice: you must start dubbo and zookeeper,register prividers first.  

###Installation  
If you have not installed [zookeeper extension](http://pecl.php.net/package/zookeeper) for php,then
```bash
sudo apt-get install php-pear php5-dev make  
sudo pecl install zookeeper
```  
Maybe occuring an error with "zookeeper support requires libzookeeper" when you install the zookeeper extension,you should install the libzookeeper needed.
```bash
cd ${your zookeeper home dir}/src/c/
./configure
make
sudo make install
```
Add zookeeper.so to your php.ini  
```bash
/usr/lib/php5/20121212/zookeeper.so
```  

#####Require dubbo-php-client composer package to your project
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
