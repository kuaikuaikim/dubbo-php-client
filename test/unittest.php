<?php

require_once "../src/register.php";
require_once "../src/invok/invokerDesc.php";
//require_once "../src/invok/cluster.php";


use \dubbo\Register;
use \dubbo\invok\invokerDesc;


$options= array(
	"registry_address" => "127.0.0.1:2181"
	);
$desc = new invokerDesc("com.dubbo.demo.HelloService","1.0.0",null);
$test = new Register($options);
$test->register($desc);
$test->zkinfo($desc);
?>