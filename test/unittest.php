<?php

require_once "../classes/register.php";
require_once "../classes/invok/invokerDesc.php";

use dubbo\Register;
use dubbo\invok\invokerDesc;

$options= array(
	"registry_address" => "127.0.0.1:2181"
	);
$desc = new invokerDesc("com.dubbo.demo.HelloService","1.0.0",null);
$test = new Register($options);
$test->subscribe($desc);


?>