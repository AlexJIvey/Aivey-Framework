<?php

/*
 * Configure settings for the website.
 * 
 * @author Alex Ivey
 */

/***********************/
/*   ERROR REPORTING   */
/***********************/
ini_set("error_reporting", E_ALL);
ini_set('display_errors', 1);

/******************************/
/*   DATABASE CONFIGURATION   */
/******************************/
define("DB_HOST", "localhost");         // Default database host
define("DB_USER", "root");              // Default database username
define("DB_PASS", "");            		// Default database password
define("DB_NAME", "");         			// Default database name
define("DB_DRIV", "pdo");               // Default database driver

/*********************/
/*   PATH SETTINGS   */
/*********************/
define("ROOT_PATH", "");
define("WEB_PATH", "http://portfolio.local");

?>
