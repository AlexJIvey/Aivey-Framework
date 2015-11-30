<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey\Controller;

/**
 * Base class for router, model and view interaction
 *
 * @author Alex Ivey
 */
class Base
{
    private $params;
    
    /**
     * Construct controller and store URL parameters.
     * 
     * @param array $params
     */
    public function __construct($params)
    {
        if(is_array($params)) {
            $this->params = $params;
        }
    }
    
    /**
     * Retrieve parameter value by its name
     * 
     * @param string $name
     * @return mixed
     */
    public function getParam($name)
    {
        return $this->params[$name];
    }
    
    /**
     * Assign parameter value
     * 
     * @param string $name
     * @param mixed $value
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }
}
