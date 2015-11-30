<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

set_include_path(get_include_path() . PATH_SEPARATOR . "./library/" . PATH_SEPARATOR . "./application/controllers");

require_once("Aivey/Autoloader.php");
$autoloader = new Aivey\Autoloader();
$autoloader->start();

$routeurl = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);
$router = new Aivey\Front\Router();

$route = new Aivey\Front\Router\Route(
        "/(?<action>[a-zA-Z0-9]+)/", 
        array(
            "controller" => "index",
            "action" => "index"
        )
);
$router->addRoute($route);

$router->dispatch();
?>
