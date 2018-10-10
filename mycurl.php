<?php
/**
 * Created by PhpStorm.
 * User: mukarram.ishaq
 * Date: 10/8/18
 * Time: 4:15 PM
 */

class Mycurl {
    protected $curl = null;
    protected $options = [
        CURLOPT_RETURNTRANSFER => 1,
    ];

    /**
     * Mycurl constructor.
     * @param string $curl_url
     */
    public function __construct($curl_url = '')
    {
        $curl_url ? $this->curl = curl_init() : $this->curl = curl_init($curl_url);
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    /**
     * @param array $options
     */
    public function setopts(array $options)
    {
        $this->options = $options+$this->options;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setopt($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers)
    {
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        $this->options[CURLOPT_HTTPHEADER]?$this->options[CURLOPT_HTTPHEADER]=$this->options[CURLOPT_HTTPHEADER]+$headers:$this->options[CURLOPT_HTTPHEADER]=$headers;
        //\Core\Log::debug($this->options, __FILE__, __LINE__);
    }

    /**
     * @param $flag
     */
    public function setPOSTFlag($flag)
    {
        //curl_setopt($this->curl, CURLOPT_POST, $flag);
        $this->options = [CURLOPT_POST=>$flag] + $this->options;
    }

    /**
     * @param $flag
     */
    public function setPUTFlag($flag)
    {
        $flag ? $this->options = [CURLOPT_CUSTOMREQUEST=>"PUT"] + $this->options:'';
    }

    public function setDELETEFlag($flag)
    {
        $flag ? $this->options = [CURLOPT_CUSTOMREQUEST=>"DELETE"] + $this->options:'';
    }

    /**
     * @param array $fields
     */
    public function setPOSTData($fields)
    {
        //curl_setopt($this->curl, CURLOPT_POSTFIELDS, $fields);
        //\Core\Log::debug($fields['grant_type'], __FILE__, __LINE__);
        $this->options = [CURLOPT_POSTFIELDS=>$fields] + $this->options;
        //\Core\Log::debug($this->options, __FILE__, __LINE__);
        $this->options[CURLOPT_HTTPHEADER] = array_merge(['Content-Type: application/json', "Content-Length: ".strlen($fields)], $this->options[CURLOPT_HTTPHEADER]);
        //\Core\Log::debug($this->options, __FILE__, __LINE__);
    }

    public function setPUTData($fields)
    {
        $this->options = [CURLOPT_POSTFIELDS=>$fields] + $this->options;
        $this->options[CURLOPT_HTTPHEADER] = array_merge(['Content-Type: application/json', "Content-Length: ".strlen($fields)], $this->options[CURLOPT_HTTPHEADER]);
    }

    /**
     * @return mixed
     */
    public function execute(){
        //apply settings
        //\Core\Log::debug($this->options, __FILE__, __LINE__);
        curl_setopt_array($this->curl, $this->options);
        //and execute now
        $result = curl_exec($this->curl);
        if (!$result) {
            \Core\Log::debug(curl_errno());
        }
        //\Core\Log::debug($result, __FILE__, __LINE__);
        return $result;
    }
}