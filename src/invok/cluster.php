<?php
namespace dubbo\invok;

class Cluster{
    protected static $providerMap = array();
    private static $_instance;

    private function __construct(){

    }

    private function __clone(){

    }

    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function addProvider($invokerDesc,$host,$schema){
        $desc = $invokerDesc->toString();
        self::$providerMap[$desc][] = $host;
    }


    public function getProvider($invokerDesc){
        $desc = $invokerDesc->toString();
        if(!isset(self::$providerMap[$desc])){
            $invokerArr = explode('_', $desc);
            throw new \Exception('Request Service Error: Can not find your service.Please Check your service name,version and group.'."\n".'RequestServiceName: '.$invokerArr[0]."\n".'RequestServiceGroup: '.$invokerArr[1]."\n".'RequestServiceVersion: '.$invokerArr[2]."\n".'RequestServiceProtocol: '.$invokerArr[3]."\n");
        }
        $key = array_rand(self::$providerMap[$desc]);
        return self::$providerMap[$desc][$key];
    }

    public function getProviders(){
        return $this->providerMap;
    }

}
