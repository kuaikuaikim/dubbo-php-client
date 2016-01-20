<?php

namespace dubbo;

require_once "invok/cluster.php";
use \dubbo\invok\Cluster;

class Register{

public $config = array(
	'registry_address' => ''
 );

public $zookeeper = null;

protected $ip;

protected $providersCluster;

public static $ServiceMap = array();

public function __construct($options = array())
{
	$this->config = array_merge($this->config,$options);
	$this->ip = $_SERVER['SERVER_ADDR'];
    $this->providersCluster = Cluster::getInstance();
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

       if(count($children) > 0){
       	foreach ($children as $key => $provider) {
            $provider = urldecode($provider);
       		$this->methodChangeHandler($invokDesc, $provider);
       	}

       $this->configurators();
}

}

public function register($invokDesc,$invoker){
    $desc = $invokDesc->toString();
    if(!array_key_exists($desc,self::$ServiceMap)){
        self::$ServiceMap[$desc] = $invoker;
    }
    $this->subscribe($invokDesc);
    $registerPath = $this->getRegistryPath($invokDesc->getService());
    $this->zookeeper->create($registerPath,null,array('word','anyone'));
    return true;
}


public function methodChangeHandler($invokerDesc, $provider){

    $schemeInfo = parse_url($provider);
    $providerConfig = array();
    parse_str($schemeInfo['query'],$providerConfig);
    var_dump($invokerDesc->isMatch($providerConfig['group'],$providerConfig['version']));
    if($invokerDesc->isMatch($providerConfig['group'],$providerConfig['version']))
    {
        $this->providersCluster->addProvider($invokerDesc,$schemeInfo['host'],$schemeInfo['scheme']);
    }


}


public function configurators(){
    return true;
}



public function getSubscribePath($serviceName){
	return '/dubbo/' .$serviceName.'/providers';
}

protected function getRegistryAddress() {
        return $this->config['registry_address'];
}


protected function getRegistryPath($serviceName, $application = array()){
        $params = http_build_query($application);
        $url = '/dubbo/'.$serviceName.'/consumers/'.urlencode('consumer://'.$this->ip.'/'.$serviceName.'?').$params;
        return $url;
}

protected function getConfiguratorsPath($serviceName){
        return '/dubbo/'.$serviceName.'/configurators';
}

protected function  getProviderTimeout(){
        return $this->config['providerTimeout'] * 1000;
}


public function zkinfo($invokerDesc){
    echo $this->getRegistryPath($invokerDesc->getService());
    var_dump($this->providersCluster->getProviders());
    var_dump($this->providersCluster);
}

}


?>