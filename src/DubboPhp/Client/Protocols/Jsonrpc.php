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

class Jsonrpc extends Invoker{

    public function __construct($url=null, $debug=false)
    {
        parent::__construct($url,$debug);
    }

    public function __call($name, $arguments)
    {
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

        // prepares the request
        $request = array(
            'method' => $name,
            'params' => $params,
            'id' => $currentId
        );
        $request = json_encode($request);
        $this->debug && $this->debug.='***** Request *****'."\n".$request."\n".'***** End Of request *****'."\n\n";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $responseContent = curl_exec($ch);
        $curlErrorCode = curl_errno($ch);
        $curlErrorMessage = curl_error($ch);
        curl_close($ch);
        if ($responseContent === FALSE)  {
            throw new DubboPhpException('Unable to connect to '.$this->url.' :'.$curlErrorMessage,$curlErrorCode);
        }

        $response = json_decode($responseContent,true);
        $jsonDecodeErrorCode = json_last_error();
        if($jsonDecodeErrorCode!==JSON_ERROR_NONE){
            $jsonDecodeErrorMessage = json_last_error_msg();
            throw new DubboPhpException('Unable to decode response content: '.$jsonDecodeErrorMessage.' :'.$responseContent,$jsonDecodeErrorCode);
        }


        // debug output
        if ($this->debug) {
            //echo nl2br($debug);
        }

        // final checks and return
        if (!$this->notification) {
            // check
            if ($response['id'] != $currentId) {
                throw new DubboPhpException('Incorrect response id (request id: '.$currentId.', response id: '.$response['id'].')');
            }
            if (isset($response['error'])) {
                //var_dump($response);
                $responseErrorCode = isset($response['error']['code'])?$response['error']['code']:0;
                $responseErrorMessage = isset($response['error']['message'])?$response['error']['message']:'';
                throw new DubboPhpException('Response error: '.$responseErrorMessage,$responseErrorCode);
            }
            return $response['result'];

        } else {
            return true;
        }
    }

}
