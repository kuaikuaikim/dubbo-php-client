<?php

require_once "../src/dubboClient.php";

use \dubbo\dubboClient;

$options= array(
	"registry_address" => "127.0.0.1:2181"
	);

$dubboCli = new dubboClient($options);
$testService = $dubboCli->getService("com.dubbo.demo.HelloService","1.0.0",null);
$ret = $testService->hello("dubbo php client");
echo $ret;

?>