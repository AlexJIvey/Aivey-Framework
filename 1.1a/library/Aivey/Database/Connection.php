<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey\Database;

use PDO;
use mysqli;
use Exception;

/**
 * Wraps connection capabilities of multiple database drivers into one class.
 *
 * @author Alex Ivey
 */
class Connection
{
    const PDO = "pdo";
    const MYSQLI = "mysqli";
    
    private $db;                // database object
    private $driver = "";       // database driver
    
    /**
     * Setup database connection when object is created
     * 
     * @param array $options
     * @throws Exception
     */
    public function __construct(array $options)
    {
        if (isset($options['host']) && is_string($options['host'])) {
            $db_host = $options['host'];
        } else {
            throw new Exception("Host option is invalid");
        }

        if (isset($options['username']) && is_string($options['username'])) {
            $db_user = $options['username'];
        } else {
            throw new Exception("Username option is invalid");
        }

        if (isset($options['password']) && is_string($options['password'])) {
            $db_pass = $options['password'];
        } else {
            throw new Exception("Password option is invalid");
        }

        if (isset($options['database']) && is_string($options['database'])) {
            $db_name = $options['database'];
        } else {
            throw new Exception("Database option is invalid");
        }

        if (isset($options['driver']) && ($options['driver'] === self::PDO || $options['driver'] === self::MYSQLI)) {
            $this->driver = $options['driver'];
        } else {
            throw new Exception("Driver option is invalid");
        }

        if (self::PDO === $options['driver']) {
            $this->db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
        } else if (self::MYSQLI === $options['driver']) {
            $this->db = new mysqli($db_host, $db_user, $db_pass, $db_name);
        }
    }
    
    /**
     * Run SQL query
     * 
     * @param string $query
     * @return boolean|object
     */
    public function query($query)
    {   
            $stmt = $this->db->query($query);
            if ($stmt) {
                return new Statement($stmt, $this->driver);
            }
            return false;
    }
    
    /**
     * Prepare SQL statement for execution
     * 
     * @param string $statement
     * @return object
     */
    public function prepare($statement)
    {
        return new Statement($this->db->prepare($statement), $this->driver);
    }
}
