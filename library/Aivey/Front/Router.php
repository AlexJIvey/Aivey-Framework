<?php
/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2 
 */

namespace Aivey\Front;

/**
 * Routes requests to a method within a specific controller class file
 *
 * @author Alex Ivey
 */
class Router
{
    private $request = "";
    private $request_parts = array();
    private $routes = array();
    
    /**
     * Setup router
     */
    public function __construct()
    {
        $request = $_SERVER['REQUEST_URI'];
        $request = trim($request, "/");
        $this->request = $request;
        $this->request_parts = explode("/", $request);
    }
    
    /**
     * Detect route and execute controller method
     */
    public function dispatch()
    {
        foreach ($this->routes as $route) {
            $defaults = $route->getDefaults();
            $controller_name = ucfirst(strtolower($defaults['controller']));
            $action_name = $defaults['action'];
            
            preg_match($route->getPattern(), $this->request, $matches);
            
            if(isset($matches['controller'])) {
                $controller_name = ucfirst(strtolower($matches['controller']));
                unset($matches['controller']);
            }
            
            if(isset($matches['action'])) {
                $action_name = $matches['action'];
                unset($matches['action']);
            }
            
            if (!is_null($controller_name) && !is_null($action_name)) {
                $controller_name = "Application\Controller\\" . $controller_name;
                $controller = new $controller_name($matches);
                $action_name = $action_name . "Action";
                $controller->$action_name();
            }
        }
    }
    
    /**
     * Retrieve route object by key
     * 
     * @param string $key
     * @return Router\Route
     */
    public function getRoute($key)
    {
        return $this->routes[$key];
    }
    
    /**
     * Retrieve URI request
     * 
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * Add Route object to array
     * 
     * @param Router\Route $route
     */
    public function addRoute(Router\Route $route)
    {
        $this->routes[] = $route;
    }
    
    /**
     * Return array of Route objects
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}

?>
