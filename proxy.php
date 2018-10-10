<?php
/**
 *
 */
require_once 'log.php';

Class Proxy
{
    protected $curl = null;
    protected $remoteServerDomain = null;
    protected $removingURLPrefix = null;
    /**
     * Proxy constructor.
     * @param $mycurl
     */
    public function __construct($remoteServerDomain, $mycurl, $removingUrlPrefix='')
    {
        $this->removingURLPrefix = $removingUrlPrefix;
        $this->remoteServerDomain = $remoteServerDomain;
        $this->curl = $mycurl;
    }


    /**
     * handle get request
     */
    public function handleGET()
    {
        $this->preprocess();
        echo $this->curl->execute();
    }

    /**
     *handle post request
     */
    public function handlePOST()
    {
        $this->preprocess();
        
        $fields = [];
        if (getallheaders()['Content-Type'] != 'application/json') {
            $fields = $_REQUEST;
        } else {
            $fields = json_decode(file_get_contents("php://input"),true);
        }
        \Core\Log::debug($fields, __FILE__, __LINE__);
        
        //$fields = $fields_string;
        $fields = json_encode($fields);
        //set post flag in curl
        $this->curl->setPOSTFlag(true);
        //set post data
        $this->curl->setPOSTData($fields);
        //now execute the request
        echo $this->curl->execute();
    }

    /**
     *
     */
    public function handlePUT()
    {
        $this->preprocess();
        $fields = [];
        if (getallheaders()['Content-Type'] != 'application/json') {
            $fields = $_REQUEST;
        } else {
            $fields = json_decode(file_get_contents("php://input"),true);
        }
        \Core\Log::debug($fields, __FILE__, __LINE__);
        //$fields = $fields_string;
        $fields = json_encode($fields);
        //set post flag in curl
        $this->curl->setPUTFlag(true);
        //set post data
        $this->curl->setPUTData($fields);
        //now execute the request
        echo $this->curl->execute();
    }


    public function handleDELETE()
    {
        $this->preprocess();
        //\Core\Log::debug(getallheaders());
        $this->curl->setDELETEFlag(true);
        //\Core\Log::debug(getallheaders());
        echo $this->curl->execute();
    }

    /**
     * jsut normal preprocessing like remove any unnecessary proxy prefix and append original remote server address etec.
     */
    private function preprocess()
    {
        $url = $this->removePrefix($_SERVER['REQUEST_URI'], $this->removingURLPrefix);
        $url = $this->remoteServerDomain . $url;
        \Core\Log::debug($url, __FILE__, __LINE__);
        $this->curl->setopts([
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ]);
        //set headers
        $headers = getallheaders();
        $headers['OAuth-Token']?$this->curl->setHeaders(['OAuth-Token: ' . $headers['OAuth-Token']]):'';
        $headers['Content-Type']?$this->curl->setHeaders(['Content-Type: ' . $headers['Content-Type']]):'';
//        $this->curl->setHeaders([
//            'OAuth-Token: ' . $headers['OAuth-Token'],
//            'Content-Type: '.$headers['Content-Type'],
//        ]);
        //\Core\Log::debug($headers);
    }

    private function removePrefix($str, $prefix){
        return substr($str, 0, strlen($prefix)) == $prefix ? substr($str, strlen($prefix)) : $str;
    }
}
