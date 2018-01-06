<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 2017/3/8
 * Time: 17:40
 */

namespace DubboPhp\Client;


abstract class Invoker{
    protected $invokerDesc;
    protected $url;
    protected $id = 0;
    protected $debug;
    protected $notification = false;
    protected $cluster;
    public function __construct($url=null, $debug=false) {
        // server URL
        $this->url = $url;
        $this->debug;
        $this->cluster = Cluster::getInstance();
    }
    public function setRPCNotification($notification = true) {
        $this->notification = (bool) $notification;
        return $this;
    }

    public function getCluster(){
        return $this->cluster;
    }

    public function setHost($url){
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Invoker
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public static function genDubboUrl($host,$invokerDesc){
        return $host.'/'.$invokerDesc->getService();
    }

    public function toString(){
        return  __CLASS__;
    }

    public function __toString()
    {
        return $this->toString();
    }

    abstract public function __call($name,$arguments);

}
