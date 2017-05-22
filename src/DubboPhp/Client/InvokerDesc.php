<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 2017/3/8
 * Time: 17:36
 */

namespace DubboPhp\Client;


class InvokerDesc
{
    private $serviceName ;
    private $group ;
    private $version ;
    private $schema = 'jsonrpc';

    public function __construct($serviceName, $version=null, $group=null,$schema='jsonrpc'){
        $this->serviceName = $serviceName ;
        $this->version = $version;
        $this->group = $group;
        !empty($schema) && $this->schema = $schema;
    }

    public function getService(){
        return $this->serviceName;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function toString(){
        $group_str = !is_null($this->group) ? $this->group : ' ';
        $version_str = !is_null($this->version) ? $this->version : ' ';
        return $this->serviceName.'_'.$group_str.'_'.$version_str.'_'.$this->schema;
    }

    public function isMatch($group,$version,$schema='jsonrpc'){
        return $this->group === $group && $this->version === $version && $this->schema === $schema;
    }

    public function isMatchDesc($desc){
        return $this->group == $desc->group && $this->version == $desc->version && $this->schema == $desc->schema;
    }

}