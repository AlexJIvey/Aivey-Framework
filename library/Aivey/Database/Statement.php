<?php

/**
 * Aivey Framework
 * 
 * @copyright Copyright (c) 2015 Alex Ivey
 * @license GPLv2
 */

namespace Aivey\Database;
use Exception;
use PDO;

/**
 * Wraps statement capabilities of multiple database drivers into one class.
 *
 * @author Alex Ivey
 */
class Statement
{
    private $driver = "";
    private $statement = null;
    
    /**
     * Setup prepared statement
     * 
     * @param string $statement
     * @param string $driver
     */
    public function __construct($statement, $driver)
    {
        $this->driver = $driver;
        $this->statement = $statement;
    }
    
    /**
     * Retrieves all rows selected by the query with named fields
     * 
     * @return array|false
     * @throws Exception
     */
    public function fetchAllAssoc()
    {
        try {
            if (Connection::PDO === $this->driver) {
                return $this->statement->fetchAll(PDO::FETCH_ASSOC);
            } else if (Connection::MYSQLI === $this->driver) {
                return $this->statement->fetch_all(MYSQLI_ASSOC);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        
    }
    
    /**
     * Return next row of query with named fields
     * 
     * @return array|false
     * @throws Exception
     */
    public function fetchAssoc()
    {
        try {
            if (Connection::PDO === $this->driver) {
                return $this->statement->fetch(PDO::FETCH_ASSOC);
            } else if (Connection::MYSQLI === $this->driver) {
                return $this->statement->fetch_array(MYSQLI_ASSOC);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Retrieves all rows selected by the query with numbered fields
     * 
     * @return array|false
     * @throws Exception
     */
    public function fetchAllNum()
    {
        try {
            if (Connection::PDO === $this->driver) {
                return $this->statement->fetchAll(PDO::FETCH_NUM);
            } else if (Connection::MYSQLI === $this->driver) {
                return $this->statement->fetch_all(MYSQLI_NUM);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Return next row of query with numbered fields
     * 
     * @return array|false
     * @throws Exception
     */
    public function fetchNum()
    {
        try {
            if (Connection::PDO === $this->driver) {
                return $this->statement->fetch(PDO::FETCH_NUM);
            } else if (Connection::MYSQLI === $this->driver) {
                return $this->statement->fetch_array(MYSQLI_NUM);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Retrieves all rows selected by the query with named and numbered fields
     * 
     * @return array|false
     * @throws Exception
     */
    public function fetchAllBoth()
    {
        try {
            if (Connection::PDO === $this->driver) {
                return $this->statement->fetchAll(PDO::FETCH_BOTH);
            } else if (Connection::MYSQLI === $this->driver) {
                return $this->statement->fetch_all(MYSQLI_BOTH);
            } 
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Return next row of query with named and numbered fields
     * 
     * @return array|false
     * @throws Exception
     */
    public function fetchBoth()
    {
        try {
            if (Connection::PDO === $this->driver) {
                return $this->statement->fetch(PDO::FETCH_BOTH);
            } else if (Connection::MYSQLI === $this->driver) {
                return $this->statement->fetch_array(MYSQLI_BOTH);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    /**
     * Bind one or more parameters to a statement
     * 
     * @param array|string $parameter
     * @param mixed $value
     * @return boolean
     */
    public function bindParam($parameter, $value = null)
    {
        if (null !== $this->statement) {
            if (Connection::PDO === $this->driver) {
                if (is_array($parameter)) {
                    foreach($parameter as $key => $value) {
                        switch(gettype($value)) {
                            case "integer":
                                $type = PDO::PARAM_INT;
                                break;
                            case "double":
                                $type = PDO::PARAM_STR;
                                break;
                            case "string":
                                $type = PDO::PARAM_STR;
                            default:
                                $type = PDO::PARAM_LOB;
                        }
                        $res = $this->statement->bindParam($key, $value, $type);
                        if (false === $res){
                            return false;
                        }
                    } 
                    return true;
                } else {
                    return $this->statement->bindParam($parameter, $value);
                }
                //return call_user_func_array(array($this->statement, "bindParam"), $args);
            } else if (Connection::MYSQL === $this->driver) {
                $params = array();
                $types = "";
                if (is_array($parameter)) {
                    foreach ($parameter as $key => $value) {
                        if (null !== $value) {
                            switch(gettype($value)) {
                                case "integer":
                                    $types .= "i";
                                    break;
                                case "double":
                                    $types .= "d";
                                    break;
                                case "string":
                                    $types .= "s";
                                default:
                                    $types .= "b";
                            }
                            $params[] = $value;
                        } else {
                            return false;
                        }
                    }
                    array_unshift($params, $types);
                    
                    return call_user_func_array(array($this->statement, "bind_param"), $params);
                }
                //
            }
        }
        
        return false;
    }
    
    /**
     * Execute the prepared statement
     * 
     * @return boolean
     * @throws Exception
     */
    public function execute()
    {
        try {
            return $this->statement->execute();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
