<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 2017/3/8
 * Time: 17:06
 */

namespace DubboPhp\Client;


class Client
{
    const VERSION_DEFAULT = '0.0.0';
    const PROTOCOL_JSONRPC = 'jsonrpc';
    const PROTOCOL_HESSIAN = 'hessian';

    /**
     * @var Register
     */
    protected $register;
    protected static $protocolSupports = [
        self::PROTOCOL_JSONRPC => true,
        self::PROTOCOL_HESSIAN => false,
    ];
    protected static $protocols = [
    ];

    /**
     * Client constructor.
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->register = new Register($options);
    }

    public function factory($options=[]){
        return new static($options);
    }

    /**
     * @param $serviceName  (service name e.g. com.xx.serviceName)
     * @param $version  (service version e.g. 1.0)
     * @param $group    (service group)
     * @param string $protocol (service protocol e.g. jsonrpc dubbo hessian)
     * @return Invoker
     */
    public function getService($serviceName, $version = self::VERSION_DEFAULT, $group = null, $protocol = self::PROTOCOL_JSONRPC, $forceVgp = false){
        $serviceVersion = !$forceVgp ? $this->register->getServiceVersion() : $version;
        $serviceGroup = !$forceVgp ? $this->register->getServiceGroup() : $group;
        $serviceProtocol = !$forceVgp ? $this->register->getServiceProtocol() : $protocol;
        $invokerDesc = new InvokerDesc($serviceName, $serviceVersion, $serviceGroup);
        $invoker = $this->register->getInvoker($invokerDesc);
        if(!$invoker){
            $invoker = $this->makeInvokerByProtocol($serviceProtocol);
            $this->register->register($invokerDesc,$invoker);
        }
        return $invoker;
    }

    /**
     * @param $protocol
     * @return Invoker instance of specific protocol
     * @throws \DubboPhp\Client\DubboPhpException
     */
    private function makeInvokerByProtocol($protocol=self::PROTOCOL_JSONRPC,$url=null, $debug=false){
        if(!isset(self::$protocolSupports[$protocol]) || self::$protocolSupports[$protocol]!=true){
            throw new DubboPhpException('Protocol Not Supported yet.');
        }
        if(!isset(self::$protocols[$protocol])){
            $providerName = 'DubboPhp\\Client\\Protocols\\'.ucfirst($protocol);
            self::$protocols[$protocol] = new $providerName($url,$debug);
        }
        return self::$protocols[$protocol];
    }

}