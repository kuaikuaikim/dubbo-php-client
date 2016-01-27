# dubbo-php-client
the dubbo php client(中文说明往下拉)

[Dubbo](https://github.com/alibaba/dubbo) is a distributed service framework empowers applications with service import/export capability with high performance RPC.  

This is only dubbo php clinet implementation. It's only support jsonRPC now.  
you can see the example for the [dubbo-jsonRPC-demo](https://github.com/quickj/dubbo_jsonrpc_demo) which i write before.  
#####Notice:  
you must start dubbo and zookeeper,register prividers first.  
#####Suggest:   
If you want have a watcher listening on the zookeeper(zookeeper will notify the counsumer in real time),you need have a runtime container(java nodejs python simply support). This model(lamp or lnmp),php is not a daemon process.  
we have found a good runtime container [Swoole](http://www.swoole.com/).Swoole is an event-driven, asynchronous & concurrent networking communication framework with higher performance written only in C for PHP.
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
Add zookeeper.so to your php.ini(/etc/php5/apache2/php.ini and /etc/php5/cli/php.ini)  
```bash
extension="/usr/lib/php5/20121212/zookeeper.so"  
```  

#####Require dubbo-php-client package to your project(composer)
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
  
  
###dubbo-php-client 中文版说明
[DUBBO](https://github.com/alibaba/dubbo)是一个分布式服务框架,致力于提供高性能和透明化的RPC远程服务调用方案,是阿里巴巴SOA服务化治理方案的核心框架  
这是dubbo的唯一php客户端，目前只支持jsonRPC协议，将来会支持多种协议。你可以查看我之前写的[dubbo-jsonRPC-demo](https://github.com/quickj/dubbo_jsonrpc_demo)例子。  
#####注意:  
使用之前你必须安装和启动dubbo,zookeeper,注册服务者。  
#####建议:
如果你想设置zookeeper watcher(zookeeper会主动通知消费者)来监听zookeeper node节点地变化,你必须得有一个一直运行地容器(java nodejs python等原生支持).但lamp,lnmp这种模式，PHP不是守护进程。  
我们发现了一个不错的PHP容器[Swoole](http://www.swoole.com/)。Swoole:PHP语言的异步、并行、高性能网络通信框架,使用纯C语言编写,提供了PHP语言的异步多线程服务器.  

###安装
如果你还没安装php的[zookeeper扩展](http://pecl.php.net/package/zookeeper)，需要
```bash
sudo apt-get install php-pear php5-dev make  
sudo pecl install zookeeper
```  

有可能安装过程中会报错"zookeeper support requires libzookeeper",说明缺少libzookeeper库，你首先需要安装该库。  
```bash
cd ${your zookeeper home dir}/src/c/
./configure
make
sudo make install
```
添加 zookeeper.so 到你的php.ini(/etc/php5/apache2/php.ini和/etc/php5/cli/php.ini)    
添加以下这行
```bash
extension="/usr/lib/php5/20121212/zookeeper.so"
```  
#####引入dubbo-php-client包到你的项目中(composer)
```bash
composer require quickj/dubbo-php-client
```  
###如何使用
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
