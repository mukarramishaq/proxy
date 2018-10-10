<?php
/**
 *
 */

/**
 * load all necessary files here..
 */
require_once('config.php');
require_once('mycurl.php');
require_once('proxy.php');
require_once 'log.php';
//.end load necessary files

//retrieve the origin of request
$origin = $_SERVER['HTTP_ORIGIN'];
\Core\Log::debug($origin);
//verify if its in allowed origns or not
if (in_array($origin, $config['allowed_origins'])) {

    //add attesting entry in the header
    header('Access-Control-Allow-Origin: '.$origin);
    //header('Access-Control-Allow-Methods: *');
    header('Access-Control-Allow-Headers: content-type, oauth-token, access-control-request-headers, access-control-request-method');
    //retrieve the method
    $method = strtoupper($_SERVER['REQUEST_METHOD']);
    \Core\Log::debug($method, __FILE__, __LINE__);
    header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, PATCH, UPDATE');
    //let the request find its path
    switch ($method) {
        case 'GET' :
            header('Content-Type: application/json');
            $proxy = new Proxy($config['remote_server_domain'], new Mycurl(), $config['removing_prefix']);
            $proxy->handleGET();
            break;
        case 'POST':
            header('Content-Type: application/json');
            //\Core\Log::debug('Hello Info');
            $proxy = new Proxy($config['remote_server_domain'], new Mycurl(), $config['removing_prefix']);
            $proxy->handlePOST();
            break;
        case 'PUT':
            header('Content-Type: application/json');
            $proxy = new Proxy($config['remote_server_domain'], new Mycurl(), $config['removing_prefix']);
            $proxy->handlePUT();
            break;
        case 'PATCH':
            header('Content-Type: application/json');
            $proxy = new Proxy($config['remote_server_domain'], new Mycurl(), $config['removing_prefix']);
            $proxy->handlePUT();
            break;
        case 'DELETE':
            header('Content-Type: application/json');
            $proxy = new Proxy($config['remote_server_domain'], new Mycurl(), $config['removing_prefix']);
            $proxy->handleDELETE();
            break;
        case 'OPTIONS':
            //header('Access-Control-Allow-Origin:'. $origin, true, 200);
            break;
    }
} else {
    header('HTTP/1.1 404 Not Found');
}


