<?php


namespace dubbo\invok;

class invokerDesc{
	private $serviceName = " ";
    	private $group = " ";
    	private $version = " ";

public function __construct($serviceName, $version, $group){
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
	return $this->serviceName .'_' .$group_str.'_' .$version_str;
}


}

?>