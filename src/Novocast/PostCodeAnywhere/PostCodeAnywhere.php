<?php

namespace Novocast\PostCodeAnywhere;

class PostCodeAnywhere
{
    
    private $params;
    
    private $request = [];
    
    private $url;
    private $services;
    private $serviceUrl;
    
    private $requestType;
    private $requestUrl;
    private $requestEndpoint;
    private $endpoints;
    

    public function __construct()
    {
        if ($this->hasValidConfig()) {
            $this->setup();

        } else {
            throw new \ErrorException('Invalid Config File');

        }

    }
    

    /**
     * Validate configuration file
     * @throws \ErrorException
     */
    protected function hasValidConfig()
    {
        
        $valid = true;

        if (!\Config::has('pca')) {
            throw new \ErrorException('Unable to find config file.');
            $valid = false;

        }

        $config = \Config::get('pca');
            
        if (!array_key_exists('params', $config) || !array_key_exists('key', $config['params']) || empty($config['params']['key'])) {
            throw new \ErrorException('Postcode Anywhere Key must be set in config file.');
            $valid = false;

        }
            
        if (!array_key_exists('url', $config) || empty($config['url'])) {
            throw new \ErrorException('Web service URL is not set in config file.');
            $valid = false;

        }

        if (!array_key_exists('services', $config) || !count($config['services'] < 1)) {
            throw new \ErrorException('Service URLs is not set in config file');
            $valid = false;

        }
            
        if (!array_key_exists('endpoint', $config) || !count($config['endpoint'] < 1)) {
            throw new \ErrorException('End point is not set in config file');
            $valid = false;

        }

        return $valid;
    }
    

    /**
     * Setup config
     */
    protected function setup()
    {
        $config = \Config::get('pca');
            
        // Assisgn values
        $this->params           = $config['params'];
        $this->url              = $config['url'];
        $this->services         = $config['services'];
        $this->endPoints        = $config['endpoint'];
        
        return $this;
    }
      
    
    /**
     * Set a parameter
     * @param string $key
     * @param string $value
     */
    public function setParam($key, $value)
    {
  
        if (array_key_exists($key, $this->params)) {
            $this->params[$key] = $value;
        }
    }
        
    /**
     * @param array $param    ['postcode'=>'AB12 3CD', 'endpoint'=> 'json']
     * @return array
     */
    public function find($params)
    {
        if (empty($params)) {
            throw new \ErrorException('No parameters are given.');
        }

        $this->setRequestType('find');
        $this->setParams($params);
        
        $response = $this->makeRequest();

        return $this->parseResponse($response);
    }
        
    /**
     * @param array $param   ['postcode'=>' FG45 6HI', 'endpoint'=> 'xml']
     * @return array
     */
    public function retrieve($params = [])
    {
        if (empty($params)) {
            throw new \ErrorException('No parameters are given.');
        }

        $this->setRequestType('retrieve');
        $this->setParams($params);
        
        $response = $this->makeRequest();

        return $this->parseResponse($response);
    }
        
    /**
     * @param array $param   ['Phone'=>'07871234567', 'Country'=> 'GB']
     * @return array
     */
    public function validate($params = [])
    {
        if (empty($params)) {
            throw new \ErrorException('No parameters are given.');
        }

        $this->setRequestType('validate');
        $this->setParams($params);
        
        $response = $this->makeRequest();

        return $this->parseResponse($response);
    }
        
    /**
     * @return array
     */
    public function getBalance()
    {

        $this->setRequestType('retrieve');
        $this->setParams(['Service' => 'Balance']);
        
        $response = $this->makeRequest();

        return $this->parseResponse($response);
    }
    
    /**
     * Set request type find or retrieve
     * @param array $action
     * @throws \ErrorException
     */
    protected function setRequestType($action)
    {
        
        if (!in_array($action, ['find','retrieve','validate'])) {
            throw new \ErrorException('Invalid request type.');
        }
        
        $this->requestType = $action;
    }
    
    /**
     * Assign parameters of request
     * @param type $params
     */
    protected function setParams($params)
    {
        // endpoint
        if (isset($params['Endpoint'])) {
            $this->setEndPoint($params['Endpoint']);
            unset($params['Endpoint']);
        } else {
            $this->setEndPoint();
        }

        // service
        if (isset($params['Service'])) {
            $this->setService($params['Service']);
            unset($params['Service']);
        } else {
            $this->setService();
        }

        // remaining
        if (is_array($params)) {
            $this->params = array_merge($this->params, $params);
        }
    }
    
    /**
     * @param string $serviceUrl
     * @throws \ErrorException
     */
    protected function setService($service = false)
    {
        if ($service === false) {
            throw new \ErrorException('No web service selected.');

        }
        
        if (!array_key_exists($service, $this->services[$this->requestType])) {
            throw new \ErrorException('Web service '.$service.' is invalid.');

        }
        
        $this->serviceUrl = $this->services[$this->requestType][$service];
    }
    
    /**
     * Set the end point for the API. This dictates format of response. JSON is default
     * @param array $param
     * @return object $this
     */
    protected function setEndPoint($endpoint = false)
    {
        $this->requestEndPoint = 'json.ws';
        
        if ($endpoint !== false && array_key_exists($endpoint, $this->endPoints)) {
            // is given endpoint correct
            $this->requestEndPoint =  $endpoint;

        }
        
        return $this;
    }
    
    /**
     * Build request URL from config and parameters
     * @return $this
     */
    protected function buildRequestURL()
    {
        $this->requestUrl = $this->url.$this->serviceUrl.$this->requestEndPoint.'?';
        $this->requestUrl .= http_build_query($this->params);

        return $this;
    }
    
    /**
     * Make an API request
     * @return object
     */
    protected function makeRequest()
    {
        $this->buildRequestURL();
      
        $ch = curl_init($this->requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);
       
        if ($output === false) {
            throw new \ErrorException(curl_error($ch));
        }

        curl_close($ch);
        return $output;

    }

    public function parseResponse($response)
    {
        return json_decode($response);
        
    }

    /**
     * Returns last request URL string
     * @return string last url string
     */
    public function getLastRequestURL()
    {
        return $this->requestUrl;
        
    }
}
