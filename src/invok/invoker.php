<?php

namespace dubbo\invok;
use \dubbo\invok\Cluster;

abstract class Invoker{
    protected $invokerDesc;
    protected $url;
    protected $id;
    protected $debug;
    protected $notification = false;
    protected $cluster;
    public function __construct($url=null, $debug=false) {
        // server URL
        $this->url = $url;
        $this->id = 1;
        $this->debug;
        $this->cluster = Cluster::getInstance();
    }
    public function setRPCNotification($notification) {
        empty($notification) ?
            $this->notification = false
            :
            $this->notification = true;
    }

    public function getCluster(){
        return $this->cluster;
    }

    public function setHost($url){
        $this->url = $url;
    }

    public static function genDubboUrl($host,$invokerDesc){
        return $host.'/'.$invokerDesc->getService();
    }

    public function toString(){
        return  __CLASS__;
    }

    abstract public function __call($name,$arguments);
}

