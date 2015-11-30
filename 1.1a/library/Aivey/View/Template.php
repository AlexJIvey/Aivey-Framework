<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey\View;

/**
 * Creates and renders the view
 *
 * @author Alex Ivey
 */
class Template {
    private $html = "";
    private $template = "";
    private $page = "";
    private $params = array();
    private $loop_params = array();
    
    public function __construct($page, $template = "default") {
        $this->page = $page;
        $this->template = $template;
    }
    
    public function setParam($name, $value) {
        $this->params[$name] = $value;
    }
    
    public function setParams(array $params) {
        foreach($params as $key => $value) {
            if (is_string($key)) {
                $this->params[$key] = $value;
            }
        } 
    }
    
    public function render() {
        $this->params['template_path'] = "/templates/" . $this->template;
       
        extract($this->params);
        
        ob_start();
        include "templates" . DIRECTORY_SEPARATOR . $this->template . DIRECTORY_SEPARATOR . $this->page . ".php";
        $this->html = ob_get_clean();
        
        return $this->html;
    }
    
    public function loop($name) {
        
        $iteration = 1;
        if (isset($this->loop_params[$name])) {
            foreach ($this->loop_params[$name] as $value) {
                extract($value);
                include "templates" . DIRECTORY_SEPARATOR . $this->template . DIRECTORY_SEPARATOR . $name . ".php";
                $iteration++;
            }
        } else {
            return false;
        }
    }
    
    public function registerLoop($name, $values) {
        if (is_array($values) && !empty($values)) {
            $this->loop_params[$name] = $values;
        } else {
            throw new \Exception('Loop values must be a non-empty array');
        }
    }
    
    public function renderLoop($name) {
        
    }
    
    public function __toString() {
        return $this->render();
    }
}
