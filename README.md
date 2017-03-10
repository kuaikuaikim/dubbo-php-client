# dubbo-php-client
the dubbo php client(中文说明往下拉)

[Dubbo](https://github.com/alibaba/dubbo) is a distributed service framework empowers applications with service import/export capability with high performance RPC.  

This is only dubbo php clinet implementation. It's only support jsonRPC now.  
you can see the example for the [dubbo-jsonRPC-demo](https://github.com/quickj/dubbo_jsonrpc_demo) which i write before.  
#####Notice:  
you must start dubbo and zookeeper,register prividers first.  
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
require_once "src/dubboClient.php";
use \dubbo\dubboClient;

// options for register consumer
// 注册消费者配置

$options= array(
    "registry_address" => "127.0.0.1:2181",
    'version' => '1.0.0',
    'group' =>null,
    'protocol' => 'jsonrpc'
);

//try is must
try {
	$dubboCli = new dubboClient($options);
	$testService = $dubboCli->getService("com.dubbo.demo.HelloService");
	$ret = $testService->hello("dubbo php client");
	$mapRet = $testService->mapEcho();
	$objectRet = $testService->objectEcho();

	var_dump($ret);
	var_dump($mapRet);
	var_dump($objectRet);
	
} catch (Exception $e) {
	print($e->getMessage());   
}

/* getService method support 2 way. If the forceVgp = true, It will assign the function parameter to service version,group and protocol. Default way is assign the $options configs to these.
   getService支持两种方式调用。如果forceVgp=true, 该方法将使用传参来绑定服务的版本号，组和协议。默认方式是使用$options数组里的配置绑定。
*/
$testServiceWithvgp = $dubboCli->getService("com.dubbo.demo.HelloService","1.0.0",null, $forceVgp = true);
var_dump($testServiceWithvgp->hello("this request from vgp"));

``` 
```  
###example
```bash
php -f example.php
```

###dubbo-php-client 中文版说明
[DUBBO](https://github.com/alibaba/dubbo)是一个分布式服务框架,致力于提供高性能和透明化的RPC远程服务调用方案,是阿里巴巴SOA服务化治理方案的核心框架  
这是dubbo的唯一php客户端，目前只支持jsonRPC协议，将来会支持多种协议。你可以查看我之前写的[dubbo-jsonRPC-demo](https://github.com/quickj/dubbo_jsonrpc_demo)例子。  
#####注意:  
使用之前你必须安装和启动dubbo,zookeeper,注册服务者。  

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
require_once "src/dubboClient.php";
use \dubbo\dubboClient;

// options for register consumer
// 注册消费者配置

$options= array(
    "registry_address" => "127.0.0.1:2181",
    'version' => '1.0.0',
    'group' =>null,
    'protocol' => 'jsonrpc'
);

//try is must
try {
	$dubboCli = new dubboClient($options);
	$testService = $dubboCli->getService("com.dubbo.demo.HelloService");
	$ret = $testService->hello("dubbo php client");
	$mapRet = $testService->mapEcho();
	$objectRet = $testService->objectEcho();

	var_dump($ret);
	var_dump($mapRet);
	var_dump($objectRet);
	
} catch (Exception $e) {
	print($e->getMessage());   
}

/* getService method support 2 way. If the forceVgp = true, It will assign the function parameter to service version,group and protocol. Default way is assign the $options configs to these.
   getService支持两种方式调用。如果forceVgp=true, 该方法将使用传参来绑定服务的版本号，组和协议。默认方式是使用$options数组里的配置绑定。
*/
$testServiceWithvgp = $dubboCli->getService("com.dubbo.demo.HelloService","1.0.0",null, $forceVgp = true);
var_dump($testServiceWithvgp->hello("this request from vgp"));

``` 
###例子
```bash
php -f example.php
```




-------------------

#按Composer规范修改版本

在本仓库分支未被quickj合并之前composer.json需要加入自定义源：
本地依赖包的仓库地址(repositories)节点中增加:

```
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/nickfan/dubbo-php-client.git"
        }
    ]

```

然后安装执行：

```
composer require -vvv "quickj/dubbo-php-client:dev-master"

```


### 调用样例-直接类调用：

```

use DubboPhp\Client\Client;

$options = [
    'registry_address' => '127.0.0.1:2181',
    'version' => '1.0.0',
    'group' =>null,
    'protocol' => 'jsonrpc'
];

try {
    $dubboCli = new Client($options);
    $testService = $dubboCli->getService("com.dubbo.demo.HelloService");
    $ret = $testService->hello("dubbo php client");
    var_dump($ret);
    $mapRet = $testService->mapEcho();
    var_dump($mapRet);

    $objectRet = $testService->objectEcho();
    var_dump($objectRet);

    /**
     * getService method support 2 way. If the forceVgp = true, It will assign the function parameter to service version,group and protocol. Default way is assign the $options configs to these.
     * getService支持两种方式调用。如果forceVgp=true, 该方法将使用传参来绑定服务的版本号，组和协议。默认方式是使用$options数组里的配置绑定。
     */
    $testServiceWithvgp = $dubboCli->getService("com.dubbo.demo.HelloService","1.0.0",null, $forceVgp = true);
    $vgpRet = $testServiceWithvgp->hello("this request from vgp");
    var_dump($vgpRet);
} catch (\DubboPhp\Client\DubboPhpException $e) {
    print($e->getMessage());
}

```

### Laravel组件模式安装

config/app.php的

providers数组中增加：

```
DubboPhp\Client\DubboPhpClientServiceProvider::class
```

aliases别名数组中增加：

```

'DubboPhpClient'=>DubboPhp\Client\Facades\DubboPhpClient::class,

'DubboPhpClientFactory'=>DubboPhp\Client\Facades\DubboPhpClientFactory::class,

```


然后命令行发布一下系统基本配置文件dubbo_cli.php到config路径：

```
php artisan vendor:publish --provider="DubboPhp\Client\DubboPhpClientServiceProvider"

```

基本安装配置完成，相关的配置在config('dubbo_cli.default')中设置，具体参考配置文件


### Laravel中的使用：

#### 单实例方式（配置读取config('dubbo_cli.default')）：

```
$testService = DubboPhpClient::getService('com.dubbo.demo.HelloService');

$ret = $testService->hello("dubbo php client");
var_dump($ret);
    
```

#### 多实例的方式（配置读取config('dubbo_cli.connections.xxx')）：

```
$clientA = DubboPhpClientFactory::factory(config('dubbo_cli.connections.xxxA'));
$testServiceA = $clientA->getService('com.dubbo.demo.HelloService');
$retA = $testServiceA->hello("dubbo php client");
var_dump($retA);

$clientB = DubboPhpClientFactory::factory(config('dubbo_cli.connections.xxxB'));
$testServiceB = $clientB->getService('com.dubbo.demo.HelloService');
$retB = $testServiceB->hello("dubbo php client");
var_dump($retB);

```
