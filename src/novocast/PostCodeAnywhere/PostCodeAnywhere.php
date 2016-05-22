<?php namespace novocast\PostCodeAnywhere;

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
            throw new Exception('Invalid Config File');

        }

    }
    

    /**
     * Validate configuration file
     * @throws Exception
     */
    protected function hasValidConfig()
    {
        
        $valid = true;

        if (!\Config::has('pca')) {
            throw new Exception('Unable to find config file.');
            $valid = false;

        }

        $config = \Config::get('pca');
            
        if (!array_key_exists('params', $config) || !array_key_exists('key', $config['params']) || empty($config['params']['key'])) {
            throw new Exception('Postcode Anywhere Key must be set in config file.');
            $valid = false;

        }
            
        if (!array_key_exists('url', $config) || empty($config['url'])) {
            throw new Exception('Web service URL is not set in config file.');
            $valid = false;

        }

        if (!array_key_exists('services', $config) || !count($config['services'] < 1)) {
            throw new Exception('Service URLs must be set in config file');
            $valid = false;

        }
            
        if (!array_key_exists('endpoint', $config) || !count($config['endpoint'] < 1)) {
            throw new Exception('End point must be set in config file');
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
     * @return object
     */
    public function find($params)
    {
        
        if (empty($params)) {
            throw new Exception('No parameters are given.');
        }

        $this->setParams($params);
        $this->setRequestType('find');
                
        return $this->makeRequest();
    }
        
    /**
     * @param array $param   ['postcode'=>' FG45 6HI', 'endpoint'=> 'xml']
     * @return object
     */
    public function retrieve($params = [])
    {
        
        if (empty($params)) {
            throw new Exception('No parameters are given.');
        }

        $this->setParams($params);
        $this->setRequestType('retrieve');
                
        return $this->makeRequest();
    }
    
    /**
     * Set request type find or retrieve
     * @param array $action
     * @throws Exception
     */
    protected function setRequestType($action)
    {
        
        if (!in_array($action, ['find','retrieve'])) {
            throw new Exception('Invalid request type.');
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
        if (isset($params['endpoint'])) {
            $this->setEndPoint($params['endpoint']);
        } else {
            $this->setEndPoint();
        }

        // service
        if (isset($params['service'])) {
            $this->setService($params['service']);
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
     * @throws Exception
     */
    protected function setService($service = false)
    {
        if ($service === false) {
            throw new Exception('No web service selected.');

        }
        
        if (!array_key_exists($serviceUrl, $this->services[ $this->requestType])) {
            throw new Exception('Web service '.$serviceUrl.' is invalid.');

        }
        
        $this->serviceUrl = $this->services[$this->requestType][$service];
    }
    
    /**
     * Set the end point for the API. This dictates format of response.
     * @param array $param
     * @return array
     */
    protected function setEndPoint($endpoint = false)
    {
        $this->requestEndPoint = 'wsdlnew.ws';
        
        if ($endpoint !== false && array_key_exists($endpoint, $this->endPoints)) {
            // is given endpoint correct
            $this->requestEndPoint =  $endpoint;
            unset($param['endpoint']);

        }
        
        return $param;
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
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);
        return $output;

    }

    /**
     * Returns last request URL string
     * @return string last url string
     */
    public function getLastRequestURL()
    {
        return $this->requestUrl();
        
    }
}
