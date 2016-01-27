<?php
namespace dubbo;
require_once "register.php";
require_once "invok/invokerDesc.php";
require_once "invok/protocols/jsonrpc.php";
use \dubbo\Register;
use \dubbo\invok\protocols\jsonRPC;
use \dubbo\invok\invokerDesc;
use \dubbo\invok\protocols;

class dubboClient{
    protected $register;
    protected $protocols;

    public function __construct($options=array())
    {
        $this->register = new Register($options);
    }

    public function getService($serviceName, $version, $group, $protocol = "jsonrpc"){
        $invokerDesc = new InvokerDesc($serviceName, $version, $group);
        $invoker = $this->register->getInvoker($invokerDesc);
        if(!$invoker){
            //$invoker = new jsonrpc();
            $invoker = $this->getInvokerByProtocol($protocol);
            $this->register->register($invokerDesc,$invoker);
        }
        return $invoker;
    }

    public function getInvokerByProtocol($protocol){

        if(!in_array($protocol, $this->protocols)){
            foreach( glob( "invok/protocols/*.php" ) as $filename ){
                $protoName = basename($filename,".php");
                array_push($this->protocols, $protoName);
                require_once $filename;
            } 
        }
      
        if(class_exists("dubbo\invok\protocols\\$protocol")){
              $class =  new \ReflectionClass("dubbo\invok\protocols\\$protocol");
              $invoker = $class->newInstanceArgs(array());
              return $invoker;
        }else{
            throw new \Exception("can't match the class according to this protocol $protocol");
        }
    }

}


?>