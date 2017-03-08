<?php

namespace dubbo;

require_once "invok/cluster.php";
require_once "invok/invoker.php";
use \dubbo\invok\Cluster;
use dubbo\invok\Invoker;


class Register{

    public $config = array(
        'registry_address' => '127.0.0.1:2181',
        'provider_timeout' => 5, //seconds
        'version' => '0.0.0',
        'group' =>null,
        'protocol' => 'jsonrpc'
     );

    public $zookeeper = null;

    protected $ip = null;

    protected $providersCluster;

    public static $ServiceMap = array();

    protected  $acl = array(
                      array(
                        'perms' => \Zookeeper::PERM_ALL,
                        'scheme' => 'world',
                        'id' => 'anyone' ) );

    public function __construct($options = array())
    {
        $this->config = array_merge($this->config,$options);
        $this->ip = $this->achieveRegisterIp();
        $this->providersCluster = Cluster::getInstance();
        $this->zookeeper= $this->makeZookeeper($this->config['registry_address']);
    }

    private function makeZookeeper($registry_address) {
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

    /**
     * @param $invokDesc
     * @param $invoker
     * @return bool
     * Register consumer information node to the zookeeper.
     */
    public function register($invokDesc, $invoker){
        $desc = $invokDesc->toString();
        if(!array_key_exists($desc,self::$ServiceMap)){
            self::$ServiceMap[$desc] = $invoker;
        }
        $this->subscribe($invokDesc);
        $providerHost = $this->providersCluster->getProvider($invokDesc);
        $invoker->setHost(Invoker::genDubboUrl($providerHost,$invokDesc));
        $registerNode = $this->makeRegistryNode($invokDesc->getService());
//        try {
            $parts = explode('/', $registerNode);
            $parts = array_filter($parts);
            $subpath = '';
            while (count($parts) > 1) {
                $subpath .= '/' . array_shift($parts);
                if (!$this->zookeeper->exists($subpath)) {
                    $this->zookeeper->create($subpath,'',$this->acl, null);
                }
            }
            if(!$this->zookeeper->exists($registerNode)) {
                $this->zookeeper->create($registerNode, '', $this->acl, null);
            }
//        }catch (ZookeeperNoNodeException $ze){
//            error_log("This zookeeper node does not exsit.Please check the zookeeper node information.");
//        }
        return true;
    }


    public function methodChangeHandler($invokerDesc, $provider){
        $schemeInfo = parse_url($provider);
        $providerConfig = array();
        parse_str($schemeInfo['query'],$providerConfig);
        $group = isset($providerConfig['group']) ? $providerConfig['group'] : null;
        $version = isset($providerConfig['version']) ? $providerConfig['version'] : null;
        if($invokerDesc->isMatch($group,$version))
        {
            $this->providersCluster->addProvider($invokerDesc,'http://'.$schemeInfo['host'].':'.$schemeInfo['port'],$schemeInfo['scheme']);
        }
    }


    public function getInvoker($invokerDesc){
        $desc = $invokerDesc->toString();
        if(!isset($ServiceMap[$desc])){
            return null;
        }
        return self::$ServiceMap[$desc];
    }


    public function configurators(){
        return true;
    }


    protected function getSubscribePath($serviceName){
        return '/dubbo/' .$serviceName.'/providers';
    }

    protected function getRegistryAddress() {
        return $this->config['registry_address'];
    }

    public function getServiceVersion() {
        return $this->config['version'];
    }

    public function getServiceGroup() {
        return $this->config['group'];
    }

    public function getServiceProtocol() {
        return $this->config['protocol'];
    }

 
    /**
     * @param $serviceName
     * @param array $application
     * @return string
     * Make a dubbo consumer address for this node which want register itself under the dubbo service
     *
     */
    private function makeRegistryNode($serviceName, $application = array()){
        $params = http_build_query($application);
        $url = '/dubbo/'.$serviceName.'/consumers/'.urlencode('consumer://'.$this->ip.'/'.$serviceName.'?').$params;
        return $url;
    }


    /**
     * @param $serviceName
     * @param array $application
     * @return string
     */
    private function makeRegistryPath($serviceName, $application = array()){
        $params = http_build_query($application);
        $path = '/dubbo/'.$serviceName.'/consumers';
        return $path;
    }


    protected function getConfiguratorsPath($serviceName){
        return '/dubbo/'.$serviceName.'/configurators';
    }

    protected function  getProviderTimeout(){
        return $this->config['provider_timeout'] * 1000;
    }


    public function zkinfo($invokerDesc){
        echo $this->getRegistryPath($invokerDesc->getService());
        var_dump($this->providersCluster->getProviders());
        var_dump($this->providersCluster);
    }


    /**
     * @param stirn $ip
     * @return Register
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return stirn
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return stirn
     * Get the consumer server local ip. If we can't get the ip from the environments,
     * we will get from the command.
     */
    private function achieveRegisterIp(){
//        try {
            $registerIp = null;
            if(php_sapi_name()=='cli'){
                if (substr(strtolower(PHP_OS), 0, 3) != 'win') {
                    $command="/sbin/ifconfig eth0 2>&1 | grep 'inet' | cut -d: -f2 | awk '{ print $1}'";
                    $ss = @exec($command,$arr,$ret);
                    $registerIp = isset($arr[0])?$arr[0]:null;
                }
            }elseif(isset($_SERVER) && isset($_SERVER['SERVER_ADDR'])){
                $registerIp = gethostbyaddr($_SERVER['SERVER_ADDR']);
            }
            if (empty($registerIp)) {
                $registerIp = gethostbyname(gethostname());
            }
//        }catch (\Exception $e){
//            error_log("We can't get your local ip address.\n");
//            error_log($e->getMessage()."\n");
//
//        }
        return $registerIp;
    }

}

?>