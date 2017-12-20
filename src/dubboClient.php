<?php
namespace dubbo;
require_once "register.php";
require_once "invok/invokerDesc.php";
require_once "invok/protocols/jsonrpc.php";
use \dubbo\Register;
use \dubbo\invok\protocols\jsonRPC;
use \dubbo\invok\invokerDesc;
use \dubbo\invok\protocols;

/**
 * Class dubboClient
 *
 * @package dubbo
 */
class dubboClient{
    protected $register;
    static $protocols = array();
    
    public function __construct($options = array()) {
        $this->register = new Register($options);
    }
    
    /**
     * @param        $serviceName (service name e.g. com.xx.serviceName)
     * @param        $version     (service version e.g. 1.0)
     * @param        $group       (service group)
     * @param string $protocol    (service protocol e.g. jsonrpc dubbo hessian)
     *
     * @return get| specific dubbo service with your params
     */
    public function getService($serviceName, $options) {
        $serviceVersion = !$options['forceVgp'] ? $this->register->getServiceVersion() : $options['version'];
        $serviceGroup = !$options['forceVgp'] ? $this->register->getServiceGroup() : $options['group'];
        $serviceProtocol = !$options['forceVgp'] ? $this->register->getServiceProtocol() : $options['protocol'];

        $invokerDesc = new InvokerDesc($serviceName, $serviceVersion, $serviceGroup);
        $invoker = $this->register->getInvoker($invokerDesc);
        if (!$invoker) {
            $invoker = $this->makeInvokerByProtocol($serviceProtocol);
            $this->register->register($invokerDesc, $invoker);
        }
        
	return $invoker;
    }
    
    
    /**
     * @param $protocol
     *
     * @return get instance of specific protocol
     */
    private function makeInvokerByProtocol($protocol) {
        
        if (array_key_exists($protocol, self::$protocols)) {
            return self::$protocols[$protocol];
        }
        
        foreach (glob(dirname(__FILE__) . "/invok/protocols/*.php") as $filename) {
            $protoName = basename($filename, ".php");
            require_once $filename;
            if (class_exists("dubbo\invok\protocols\\$protoName")) {
                $class = new \ReflectionClass("dubbo\invok\protocols\\$protoName");
                $invoker = $class->newInstanceArgs(array());
                self::$protocols[$protoName] = $invoker;
            }
        }

        return self::$protocols[$protocol];

    }

}


?>