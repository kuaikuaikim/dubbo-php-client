<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 2017/3/9
 * Time: 20:31
 */

require_once __DIR__.'/vendor/autoload.php';

use DubboPhp\Client\Client;

$options = [
    'registry_address' => '127.0.0.1:2181',
    'version' => '1.0.0',
    'group' =>null,
    'protocol' => 'jsonrpc'
];

try {
    $dubboCli = new Client($options);
    $testService = $dubboCli->getService("com.dubbo.demo.HelloService");
    $ret = $testService->hello("dubbo php client");
    var_dump($ret);
    $mapRet = $testService->mapEcho();
    var_dump($mapRet);

    $objectRet = $testService->objectEcho();
    var_dump($objectRet);

    /**
     * getService method support 2 way. If the forceVgp = true, It will assign the function parameter to service version,group and protocol. Default way is assign the $options configs to these.
     * getService支持两种方式调用。如果forceVgp=true, 该方法将使用传参来绑定服务的版本号，组和协议。默认方式是使用$options数组里的配置绑定。
     */
    $testServiceWithvgp = $dubboCli->getService("com.dubbo.demo.HelloService","1.0.0",null, $forceVgp = true);
    $vgpRet = $testServiceWithvgp->hello("this request from vgp");
    var_dump($vgpRet);
} catch (\Exception $e) {
    print($e->getMessage());
}