<?php
use \System\App;
use \System\Router;
use \System\Session;
use \System\Http\Request;
use \System\Http\Response;

/**
* Include required files
*/
$config_files = array("Database", "Router");

foreach($config_files as $file):
    include "../Config/{$file}.Config.php";
endforeach;

include "App.php";

/** 
* Enable psr-0 autoloading
*/
$autoload_function = function($class)
{
    $file = dirname(__DIR__).DS.$class.".php";

    if(is_file($file))
        include_once $file;
};

spl_autoload_register($autoload_function);

/**
* Setup the session
*/
session_set_save_handler(new Session);
session_start();

/**
* Create app context
*/
$router = new Router(new Request(), new Response());

$app = App::getContext($router);

// configure app
$app->setViewDirectory('../Views');

return $app;

