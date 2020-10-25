<?php

namespace App\Helper;

class RequestHelper
{
    const DOMAIN = 'https://ivaylo-slavchev-emag.upnetix.tech';

    /**@var RequestHelper */
    private static $requestHelper = null;

    private $url;
    private $uri;
    private $getParams = [];
    private $postParams = [];
    private $requestMethod;

    private function __construct()
    {
        $this->url = self::DOMAIN . $_SERVER['REQUEST_URI'];
        $this->uri = $this->getUri();
        $this->setGetParams($_GET);
        $this->setPostParams($_POST);
        $this->setRequestMethod($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return RequestHelper
     */
    public static function requestInstance()
    {
        if (self::$requestHelper === null) {
            self::$requestHelper = new RequestHelper();
        }
        return self::$requestHelper;
    }

    /**
     * @param $getPrams
     */
    public function setGetParams($getPrams)
    {
        $this->getParams = $getPrams;
    }

    /**
     * @return array
     */
    public function getGetParams()
    {
        return $this->getParams;
    }

    /**
     * @return array
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    /**
     * @param $postPrams
     */
    public function setPostParams($postPrams)
    {
        $this->postParams = $postPrams;
    }

    /**
     * @param $requestMethod
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = strtolower(trim($requestMethod));
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param $param
     *
     * @return string
     */
    private function sanitize($param)
    {
        $param = htmlentities($param);
        $param = trim($param);

        return $param;
    }

    /**
     * @param $key
     * @param null $returnValue
     *
     * @return string|null
     */
    public function getGetSingleValue($key, $returnValue = null)
    {
        if (isset($this->getParams[$key])) {
            $returnValue = $this->sanitize($this->getParams[$key]);
        }

        return $returnValue;
    }

    /**
     * @param $key
     * @param null $returnValue
     *
     * @return array|string|null
     */
    public function getPostSingleValue($key, $returnValue = null)
    {
        if (isset($this->postParams[$key])) {
            if (is_array($this->postParams[$key])) {
                $returnValue = $this->postParams[$key];
            } else {
                $returnValue = $this->sanitize($this->postParams[$key]);
            }
        }

        return $returnValue;
    }

    /**
     * @param string
     *
     * @return string|null
     */
    public function getFile($key)
    {
        $file = $_FILES[$key]['tmp_name'];
        if (!file_exists($file)) {
            $file = null;
        }
        return $file;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if ($uri !== null || $uri !== "/") {
            $getParamStartPosition = strpos($uri,'?');
            if($getParamStartPosition){
                $uri = substr($uri, 1, $getParamStartPosition-1);
            } else {
                $uri = substr($uri, 1);
            }
            $uri = ltrim($uri, '/');
        }

        return $uri;
    }
}