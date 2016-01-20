<?php
namespace dubbo;

class Register{

public $config = array(
	'registry_address' => ''
 );

public  $zookeeper = null;

private $ip;

public function __construct($options = array())
{
	$this->config = array_merge($this->config,$options);
	$this->ip = $_SERVER['SERVER_ADDR'];
	$this->zookeeper= $this->getZookeeper($this->config['registry_address']);

}

public function getZookeeper($registry_address) {
            return new \Zookeeper ($registry_address);
 }

public function subscribe($invokDesc){
       $desc = $invokDesc->toString();
       $serviceName = $invokDesc->getService();
       $path = $this->getSubscribePath($serviceName);
       $children = $this->zookeeper->getChildren($path);
       var_dump($children);
       if(count($children) > 0){
       	foreach ($children as $key => $provider) {
       		# code...
       	}
       }

}

public function register(){

}

public function getSubscribePath($serviceName){
	return '/dubbo/' .$serviceName.'/providers';
}

private function getRegistryAddress() {
        return $this->config['registry_address'];
 }
   
   /*
private function getRegistryPath($serviceName, $application = array()){
        $params = http_build_query($application);
        var url = '/dubbo/'.$serviceName.'/consumers/'.urlencode('consumer://'.$this->ip.'/' .$serviceName .'?') .$params;
        return url;
 }
  */
 private function  getProviderTimeout(){
        return $this->config['providerTimeout'] * 1000;
    }





}


?>