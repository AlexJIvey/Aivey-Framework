<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey\Front\Router;

use InvalidArgumentException;

/**
 * Contains potential route pattern and interpretation capabilities
 *
 * @author Alex Ivey
 */
class Route
{
    private $pattern = "";
    private $defaults = array("controller" => null, "action" => null);
    
    /**
     * Setup route
     * 
     * @param string $pattern
     * @param array $defaults
     * @throws InvalidArgumentException
     */
    public function __construct($pattern, $defaults = null)
    {
        if (!is_string($pattern)) {
            throw new InvalidArgumentException("String expected for \$pattern, but " . gettype($pattern) . " was given");
        }
        if (!is_null($defaults)) {
            if (!is_array($defaults)) {
                throw new InvalidArgumentException("Array expected for \$defaults, but " . gettype($defaults) . " was given");
            }
        }
        
        $this->pattern = $pattern;
        
        $this->defaults = array_merge($this->defaults, $defaults);        
    }
    
    /**
     * Get default route parameters
     * 
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }
    
    /**
     * Get route test pattern
     * 
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }
    
    /**
     * Get default controller
     * 
     * @return string
     */
    public function getController()
    {
        return $this->defaults['controller'];
    }
    
    /**
     * Get default action
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->defaults['action'];
    }
}
