#!/usr/bin/php

<?php

require('FCGIClient.php');

$requests = array();

// Perform 4 parrallel requests.
for ($i = 0 ; $i < 1; $i++) {

    $client = new \framework\FCGIClient('localhost', '8888');

    $client->request(
        array(
            'GATEWAY_INTERFACE' => 'FastCGI/1.0',
            'REQUEST_METHOD' => 'GET',
            'SCRIPT_FILENAME' => '/home/preetam/phpinfo.php',
            'SERVER_SOFTWARE' => 'php/fcgiclient',
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '8888',
            'SERVER_ADDR' => '127.0.0.1',
            'SERVER_PORT' => '8888',
            'SERVER_NAME' => php_uname('n'),
            'SERVER_PROTOCOL' => 'HTTP/1.1'
        ),
        ''
    );

    $requests[] = $client;
}

// Collect the responses from the previous requests.
for ($i = 0; $i < 1; $i++) {
    $response = $requests[$i]->response();
    
    if (!empty($response)) {
        //echo print_r($response, true);
        echo print_r($response["body"],true);
    }
}

?>
