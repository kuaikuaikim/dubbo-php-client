<?php
/**
 * @description
 * dubbo php client example
 *
 * @author quickj
 * @date 2016-1-20
 */

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


?>