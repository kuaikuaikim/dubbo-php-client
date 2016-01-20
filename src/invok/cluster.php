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
        $this->providerMap[$desc][] = $host;
    }


    public function getProvider($invokerDesc){
        $desc = $invokerDesc->toString();
        $key = array_rand($this->providerMap[$desc]);
        return $this->providerMap[$desc][$key];
    }

    public function getProviders(){
        return $this->providerMap;
    }

}
