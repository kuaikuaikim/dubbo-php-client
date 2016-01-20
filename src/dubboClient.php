<?php
namespace dubbo;
require_once "register.php";
require_once "invok/invokerDesc.php";
require_once "invok/protocols/jsonRPC.php";
use \dubbo\Register;
use \dubbo\invok\protocols\jsonRPC;
use \dubbo\invok\invokerDesc;

class dubboClient{
    protected $register;

    public function __construct($options=array())
    {
        $this->register = new Register($options);
    }

    public function getService($serviceName, $version, $group){
        $invokerDesc = new InvokerDesc($serviceName, $version, $group);
        $invoker = $this->register->getInvoker($invokerDesc);
        if(!$invoker){
            $invoker = new jsonRPC();
            $this->register->register($invokerDesc,$invoker);
        }
        return $invoker;
    }


}


?>