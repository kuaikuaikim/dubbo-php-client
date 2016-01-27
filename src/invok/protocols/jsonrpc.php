<?php

namespace dubbo\invok\protocols;
require_once dirname(dirname(__FILE__))."/invoker.php";

use \dubbo\invok\Invoker;

class jsonrpc extends Invoker{

    public function __construct()
    {
        parent::__construct();
    }


    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        if (!is_scalar($name)) {
            throw new \Exception('Method name has no scalar value');
        }

        // check
        if (is_array($arguments)) {
            // no keys
            $params = array_values($arguments);
        } else {
            throw new \Exception('Params must be given as array');
        }

        // sets notification or request task
        if ($this->notification) {
            $currentId = NULL;
        } else {
            $currentId = $this->id;
        }

        // prepares the request
        $request = array(
            'method' => $name,
            'params' => $params,
            'id' => $currentId
        );
        $request = json_encode($request);
        $this->debug && $this->debug.='***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";

        // performs the HTTP POST
        $opts = array ('http' => array (
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $request
        ));
        $context  = stream_context_create($opts);
        if ($fp = fopen($this->url, 'r', false, $context)) {
            $response = '';
            while($row = fgets($fp)) {
                $response.= trim($row)."\n";
            }
            $this->debug && $this->debug.='***** Server response *****'."\n".$response.'***** End of server response *****'."\n";
            $response = json_decode($response,true);
        } else {
            throw new \Exception('Unable to connect to '.$this->url);
        }

        // debug output
        if ($this->debug) {
            //echo nl2br($debug);
        }

        // final checks and return
        if (!$this->notification) {
            // check
            if ($response['id'] != $currentId) {
                throw new \Exception('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')');
            }
            if (!is_null($response['error'])) {
                throw new \Exception('Request error: '.$response['error']);
            }

            return $response['result'];

        } else {
            return true;
        }
    }

}


