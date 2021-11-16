<?php

require_once __DIR__ . '/BaseClass.php';
require_once __DIR__ . '/FileClass.php';

//Procesos
class WorkClass
{
    private $apiKey;
    private $requestContent;
    private $arrayContent;
    private $apiRequest;
    private $getRequest;
    private $file;

    public function __construct($apiKey, $requestContent)
    {
        $this->apiKey = $apiKey;
        $this->requestContent = $requestContent;
        $this->getRequest = new BaseClass();

        $this->file = new FileClass();
    }

    public function Run()
    {
        if (count($this->requestContent) > 0) {
            $params = [
                'key' => $this->apiKey,
                'mraid' => '2', //2     //1     //0
                'aid' => '19a6c729-1e27-e936-84c1-122b2a9bbc8c',
                'cb' => '58a32ea76badd',
                'aidl' => '1', //1      //0    // 'aidl' => '23482834829',
                'qapid' => '12341234',
                'secure' => '1', //0
                // 'ab' => 'com.tappx.example',
                'url' =>
                    'https://edition.cnn.com/videos/sports/2017/07/11/nico-hulkenberg-tennis-hit-renault-f1-amanda-davies-intv-spc.cnn',
            ];
            foreach ($this->requestContent as $key => $content) {
                if ($key == 'imp') {
                    $width = $content[0]['banner']['w'];
                    $height = $content[0]['banner']['h'];
                    $params['sz'] = $width . 'x' . $height;
                } elseif ($key == 'device') {
                    $params['os'] = $content['os'];
                    $params['ip'] = $content['ip'];
                    $params['ua'] = $content['ua'];
                    $params['lat'] = $content['geo']['lat'];
                    $params['lon'] = $content['geo']['lon'];
                } elseif ($key == 'app') {
                    $params['ab'] = $content['bundle'];
                    // echo "<div style='background-color: white; color:red;'>";
                    // echo '<p>';
                    // echo '<pre>';
                    // var_dump($content['bundle']);
                    // print_r($params);
                    // echo '</pre>';
                    // echo '</p>';
                    // echo '<div/>';
                    $params['name'] = $content['name'];
                    // $params['url'] = $content['storeurl'];
                    $params['source'] = $key;
                } elseif ($key == 'id') {
                    $params['id'] = $content;
                } elseif ($key == 'tmax') {
                    $params['timeout'] = $content;
                } elseif ($key == 'test') {
                    $params['test'] = $content;
                }
            }
            // echo "<div style='background-color: white; color:red;'>";
            // echo '<p>';
            // echo '<pre>';
            // //var_dump($params);
            // print_r($params);
            // echo '</pre>';
            // echo '</p>';
            // echo '<div/>';
            //die();
        }
        $this->getRequest->queryParams($params);
        $this->getRequest->getMethod('ssp/req.php');
        $responseApi = $this->getRequest->getResponseApi();
        $error = 0;
        $errorMsg = '';
        if ($this->getRequest->isError()) {
            $error = 1;
            $errorMsg = $this->getRequest->getError();
        }
        $response = [
            'test' => $params['test'],
            'error' => $error,
            'error_reason' => $errorMsg,
            'AD' => $responseApi,
        ];
        $this->file->writeResponse($response);

        return $response;
        var_dump($response);
    }
}
