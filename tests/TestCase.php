<?php

namespace DubboPhp\Client\Tests;

use DubboPhp\Client\Client;

class TestCase extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        parent::setUp();
        require_once __DIR__.'/../vendor/autoload.php';
    }
    public function tearDown()
    {
        parent::tearDown();
    }

    public function testDefault()
    {
        $this->assertEquals(1,1*1);

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
            $this->assertEquals('Hello, dubbo php client!',$ret);
            $mapRet = $testService->mapEcho();
            $this->assertEquals([
                'key1'=>'Hello',
                'key2'=>'World',
            ],$mapRet);
            $objectRet = $testService->objectEcho();
            $this->assertEquals([
                'key1'=>'hello world',
                'key2'=>'2016',
            ],$objectRet);
            /**
             * getService method support 2 way. If the forceVgp = true, It will assign the function parameter to service version,group and protocol. Default way is assign the $options configs to these.
             * getService支持两种方式调用。如果forceVgp=true, 该方法将使用传参来绑定服务的版本号，组和协议。默认方式是使用$options数组里的配置绑定。
             */
            $testServiceWithvgp = $dubboCli->getService("com.dubbo.demo.HelloService","1.0.0",null, $forceVgp = true);
            $vgpRet = $testServiceWithvgp->hello("this request from vgp");
            $this->assertEquals('Hello, this request from vgp!',$vgpRet);
        } catch (\Exception $e) {
            print($e->getMessage());
        }

    }
}
