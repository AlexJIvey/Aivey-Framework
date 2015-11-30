<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey\Form;
use Exception;

/**
 * Base class for creating and outputting a HTML form.
 *
 * @author Alex Ivey
 */
class Base {
    
    private $action = "";
    private $method = "post";
    private $sent = false;
    protected $form_id = "";
    protected $elements = array();
    protected $validate_field = "";
    protected $errors = array();
    protected $refill = true;
    
    /**
     * Add a form element to the element array
     * 
     * @param array $params
     */
    public function addElement(array $params) {
        $this->elements[] = $params;
    }
    
    /**
     * Set the HTTP action taken by the form when submitted
     * 
     * @param type $action
     */
    public function setAction($action) {
        $this->action = $action;
    }
    
    /**
     * Set the form ID used for the form's HTML id parameter
     * 
     * @param string $form_id
     */
    public function setFormId($form_id) {
        $this->form_id = $form_id;
    }
    
    /**
     * Check whether a POST of GET submission exists (depending on set method)
     * 
     * @return boolean
     */
    public function getSent() {
        return $this->sent;
    }
    
    /**
     * Add an error message to the form
     * 
     * @param string $text
     */
    public function addError($text) {
        $this->errors[] = $text;
    }
    
    /**
     * Return the errors array
     * 
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Render HTML output
     * 
     * @return string
     */
    public function render() {
        $html = '<form action="' . $this->action . '" method="' . $this->method . '" id="' . $this->form_id . '">'; 

        foreach($this->elements as $element) {
            $params = array();
            
            if (isset($element['id'])) {
                $params[] = 'id="' . $element['id'] . '"'; 
            }
            if (isset($element['placeholder'])) {
                $params[] = 'placeholder="' . $element['placeholder'] . '"'; 
            }
            if (isset($element['value'])) {
                $params[] = 'value="' . $element['value'] . '"';
            }
            if (isset($element['name'])) {
                $params[] = 'name="' . $element['name'] . '"';
            }
            
            $value = "";
            if ($this->refill && $this->sent) {
                if ($this->method == "post") {
                    $value = filter_input(INPUT_POST, $element['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                } else if ($this->method = "get") {
                    $value = filter_input(INPUT_GET, $element['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                }
            }
            
            
            if ($element['type'] == "textarea"){
                $html .= '<textarea ' . implode(" ", $params) . '>' . $value . '</textarea>';
            } else {
                if (strlen($value) > 0) {
                    $params[] = 'value="' . $value . '"';
                }
                $params[] = 'type="' . $element['type'] . '"'; 
                $html .= '<input ' . implode(" ", $params) . '></input>';
            }
        }
        
        $html .= '</form>';
        
        return $html;
    }
    
    /**
     * Route variable assignments to variable's set function
     * 
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws Exception
     */
    public function __set($name, $value) {
        
        $name_parts = explode("_", $name);
        if (count($name_parts) > 1) {
            foreach ($name_parts as $key => $value) {
                $name_parts[$key] = ucfirst($value);
            }
            $name = implode("", $name_parts);
        }
        
        $method = "set" . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        } else {
            throw new Exception("The property, $name, is not valid");
        }
    }
    
    /**
     * Route variable requests to variable's get function
     * 
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function __get($name) {
        
        $name_parts = explode("_", $name);
        if (count($name_parts) > 1) {
            foreach ($name_parts as $key => $value) {
                $name_parts[$key] = ucfirst($value);
            }
            $name = implode("", $name_parts);
        }
        
        $method = "get" . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            throw new Exception("The property, $name, is not valid");
        }
    }
    
    /**
     * Call render function when object is outputted as a string
     * 
     * @return type
     */
    public function __toString() {
        return $this->render();
    }
    
    /**
     * Set object variables when class is constructed
     */
    public function __construct() {
        $this->action = filter_input(INPUT_SERVER, "PHP_SELF");
        
        if ($this->method == "post") {
            if (filter_input(INPUT_POST, $this->validate_field)) {
                $this->sent = true;
            }
        } else if ($this->method == "get") {
            if (filter_input(INPUT_GET, $this->validate_field)) {
                $this->sent = true;
            }
        }
    }
}
