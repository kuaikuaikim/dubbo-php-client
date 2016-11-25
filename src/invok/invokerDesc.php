<?php


namespace dubbo\invok;

class invokerDesc{
	private $serviceName = " ";
    private $group = " ";
    private $version = " ";
	private $schema = 'jsonrpc';

    public function __construct($serviceName, $version=null, $group=null){
        $this->serviceName = $serviceName ;
        $this->version = $version;
        $this->group = $group;
    }

    public function getService(){
        return $this->serviceName;
    }

    public function toString(){
        $group_str = isset($this->group) ? $this->group : ' ';
        $version_str = isset($this->version) ? $this->version : ' ';
        return $this->serviceName.'_'.$group_str.'_'.$version_str.'_'.$this->schema;
    }

    public function isMatch($group,$version){
        return $this->group === $group && $this->version === $version;
    }

    public function isMatchDesc($desc){
        return $this->group == $desc->group && $this->version == $desc->version;
    }

}

?>