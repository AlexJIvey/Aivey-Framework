<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey\Database;

/**
 * Contains database connections
 *
 * @author Alex Ivey
 */
class ConnectionContainer
{
    protected static $database;
    
    /**
     * Retrieves database connection.
     * 
     * @param array $options
     * @return boolean|object
     */
    public static function getConnection(array $options = array()) 
    {
        if (null === self::$database) {
            if(empty($options)) {
                $options["host"] =      DB_HOST;
                $options['username'] =  DB_USER;
                $options["password"] =  DB_PASS;
                $options["database"] =  DB_NAME;
                $options["driver"] =    DB_DRIV;
            }
            self::$database = new \Aivey\Database\Connection($options);
            return self::$database;
        } else {
            return self::$database;
        }
        
        return false;
    }
}
