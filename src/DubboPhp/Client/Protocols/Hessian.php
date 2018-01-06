<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 2017/3/8
 * Time: 17:48
 */

namespace DubboPhp\Client\Protocols;

use DubboPhp\Client\DubboPhpException;
use DubboPhp\Client\Invoker;

class Hessian extends Invoker
{
    public function __construct($url=null, $debug=false)
    {
        //@todo implement method
        throw new DubboPhpException('Protocol not implemented yet.');

        parent::__construct($url,$debug);
    }

    public function __call($name, $arguments)
    {
        //@todo implement method
        throw new DubboPhpException('Protocol not implemented yet.');

        if (!is_scalar($name)) {
            throw new DubboPhpException('Method name has no scalar value');
        }

        // check
        if (is_array($arguments)) {
            // no keys
            $params = array_values($arguments);
        } else {
            throw new DubboPhpException('Params must be given as array');
        }

        // sets notification or request task
        if ($this->notification) {
            $currentId = NULL;
        } else {
            $currentId = $this->id;
            $this->id++;
        }
        return null;
    }

}