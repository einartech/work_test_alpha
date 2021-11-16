<?php

class FileClass
{
    //PROPS
    private $fp;

    //METODS
    public function __construct()
    {
        $pathFile = $_SERVER['DOCUMENT_ROOT'] . '/Output/req.json';
        ($this->fp = fopen($pathFile, 'w')) or
            die('No ha sido posible crear el fichero');
    }
    public function __destruct()
    {
        fclose($this->fp);
    }

    public function writeResponse($response)
    {
        fwrite($this->fp, json_encode($response));
    }
}
