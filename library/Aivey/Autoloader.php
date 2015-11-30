<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey;

/**
 * Handles invalid class requests
 *
 * @author Alex Ivey
 */
class Autoloader {
    
    /**
     * Autoload classes based on autoloader settings
     * 
     * @param string $class
     */
    private function autoload($class) {
        $parts = explode("\\", $class);
        $path = implode(DIRECTORY_SEPARATOR,$parts);
        include $path.".php";
    }
    
    /**
     * Register autoload function
     * 
     * @return boolean
     */
    public function start() {
        return spl_autoload_register(array($this, "autoload"));
    }
}

?>