<?php

class BaseClass
{
    //PROPS
    private $ch = '';
    private $url = '';
    private $data_array = '';
    private $data = '';
    private $resp = '';
    private $e = '';
    private $decoded = '';
    private $response = '';
    private $error = '';
    private $isError = false;
    private $header = [];

    private $URL_BASE = 'http://insert-base.net';

    //METHODS

    /**
     * __Constructor
     *
     * Curl init
     */
    public function __construct()
    {
        $this->ch = curl_init();
    }

    /**
     * __Destructor
     * Curl Destroy
     */
    public function __destruct()
    {
        curl_close($this->ch);
    }

    /**
     * __Execute Curl
     * Curl Execution
     */
    public function executeCurl()
    {
        $this->response = curl_exec($this->ch);
    }

    /**
     * __Is Response Json
     * Check if response is json
     */
    private function isJson($response)
    {
        json_decode($response);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * __Is Response Html
     * Check if response is Html
     */
    private function isHTML($string)
    {
        if ($string != strip_tags($string)) {
            // is HTML
            return true;
        } else {
            // not HTML
            return false;
        }
    }

    /**
     * __Query Params
     *
     * Data Array that we want send
     */
    public function queryParams($params)
    {
        $this->data_array = $params;
    }

    /**
     * __Codify Query
     * Url chain codified
     * Data Array that we want send
     */
    public function codifyQuery()
    {
        $this->data = http_build_query($this->data_array);
    }

    /**
     * __Get Url
     * Url join basics
     * @param url - brings the rest of the url to form the whole basic endpoint
     */
    private function getUrl($url)
    {
        return $this->URL_BASE . '/' . $url;
    }

    /**
     * __Get Method
     * Specific Curl options for GET request
     * Capture URL and give it to the navigator
     * Error checker and response decode
     * Check Type of Response with __isJson
     * @param url - reference to __getUrl function
     */
    public function getMethod($url)
    {
        $url = $this->getUrl($url) . '?' . $this->data;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 80);
        // curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        // Then, after your curl_exec call:
        $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
        $header = substr($this->response, 0, $header_size);
        $body = substr($this->response, $header_size);
        var_dump($body);

        $this->response = curl_exec($this->ch);

        if (curl_error($this->ch)) {
            $this->isError = true;
            $this->error = curl_error($this->ch);
        } else {
            var_dump($this->response);

            if ($this->isJson($this->response)) {
                var_dump($this->response);
                $resp = json_decode($this->response, true);

                //Check result
                /*if (count($decode) > 0) {
                    foreach ($decoded as $key => $val) {
                        echo "$key:$val<br>";
                    }
                }*/
            }
        }
        return $this->response;
    }

    /**
     * __
     *
     */
    // public function setHeader($header)
    // {
    //     $this->header = $header;
    // }

    /**
     * __Get Response from API
     *
     */
    public function getResponseApi()
    {
        return $this->response;
    }

    /**
     * __Is this an Error?
     *
     */
    public function isError()
    {
        return $this->isError;
    }

    /**
     * __Get the Error
     *
     */
    public function getError()
    {
        return $this->error;
    }
}
