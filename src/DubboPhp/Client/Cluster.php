<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 2017/3/8
 * Time: 17:42
 */

namespace DubboPhp\Client;


class Cluster
{
    protected static $providerMap = [];
    private static $_instance;

    private function __construct(){
    }

    private function __clone(){

    }

    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    public function addProvider(InvokerDesc $invokerDesc,$host){
        $desc = $invokerDesc->toString();
        self::$providerMap[$desc][] = $host;
    }


    public function getProvider(InvokerDesc $invokerDesc){
        $desc = $invokerDesc->toString();
        if(!isset(self::$providerMap[$desc])){
            $invokerArr = explode('_', $desc);
            throw new DubboPhpException('Request Service Error: Can not find your service.Please Check your service name,version and group.'."\n".'RequestServiceName: '.$invokerArr[0]."\n".'RequestServiceGroup: '.$invokerArr[1]."\n".'RequestServiceVersion: '.$invokerArr[2]."\n".'RequestServiceProtocol: '.$invokerArr[3]."\n");
        }
        $key = array_rand(self::$providerMap[$desc]);
        return self::$providerMap[$desc][$key];
    }

    public function getProviders(){
        return self::$providerMap;
    }

}