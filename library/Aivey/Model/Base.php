<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey 
 * @license GPLv2
 */

namespace Aivey\Model;

use Exception;
use Aivey\Database\ConnectionContainer;
use Aivey\Database\Connection;

/**
 * Provides base application model functionality
 *
 * @author Alex Ivey
 */
class Base {
    protected $db;            // Database object
    protected $table_name;    // Name of table to access (define in child)
    protected $primary_key;   // Primary key(s) of the table (define in child)
    protected $order_by;      // Table field to organize query
    
    public function __construct() {
        $this->db = ConnectionContainer::getConnection();
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
        $method = "get" . ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        } else {
            throw new Exception("The property, $name, is not valid");
        }
    }
    
    /**
     * Assign database connection object to variable
     * 
     * @param Connection $database
     */
    public function setDatabase(Connection $database) {
        $this->db = $database;
    }
    
    /**
     * Retrieve records of table based on WHERE conditions
     * 
     * @param string $where
     * @param array|string $binds
     * @return array|false
     */
    public function find(array $params, $binds = null) {
        $sql = "";
        $elements = array();
        
        foreach ($params as $key => $value) {
            if ($key == "WHERE") {
                $elements[] = "WHERE";
                if (is_array($value)) {
                    $elements[] = implode(" AND ", $value);
                } else {
                    $elements[] = $value;
                }
            } else if ($key == "ORDER BY") {
                $elements[] = "ORDER BY";
                if (is_array($value)) {
                    $elements[] =  implode(", ", $value);
                } else {
                    $elements[] =  $value;
                }
            }
        }
        
        $sql = implode(" ", $elements);
        
        if (null === $binds) {
            $stmt = $this->db->query("SELECT * FROM $this->table_name $sql");
            return $stmt->fetchAllAssoc();
        } else {
            $stmt = $this->db->prepare("SELECT * FROM $this->table_name $sql");
            $stmt->bindParam($binds);
            $stmt->execute;
            return $stmt->fetchAllAssoc();
        }
    }
    
    /**
     * Retrieve all records from table
     * 
     * @param string $order_by
     * @return array|boolean
     */
    public function fetchAll($order_by = null) {
        $query = "SELECT * FROM $this->table_name";
        if (null !== $order_by) {
            $query .= ' ORDER BY ' . $order_by;
        }
        
        $stmt = $this->db->query($query);
        
        if ($stmt) {
            return $stmt->fetchAllAssoc();
        } else {
            return false;
        }
    }
}
