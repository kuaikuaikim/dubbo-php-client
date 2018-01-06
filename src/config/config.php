<?php
return [

    'default'=>[
        /**
         * zookeeper service address (host:port) default localhost:2181
         */
        'registry_address' => env('ZOOKEEPER_ADDRESS','localhost:2181'),

        /**
         * service provider invoke timeout
         */
        'provider_timeout' => 5, //seconds

        /**
         * service version
         */
        'version' => '1.0.0',
        /**
         * service group name
         */
        'group' => null,
        /**
         * protocol support jsonrpc/hessian(undone)
         */
        'protocol' => 'jsonrpc',
    ],

    'connections'=>[
    ],
];