<?php
namespace System;

include 'Constants.php';

class App{
    private $router;
    private static $context;

    private function __construct(\System\Router $router)
    {
        $this->router = $router;
    }

    public static function getContext(\System\Router $router)
    {
        if(is_null(self::$context)):
            self::$context = new App($router);
        endif;

        return self::$context;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function setViewDirectory($dir)
    {
        if(is_dir($dir)):
            $this->router->responseHandler->setViewDir(realpath($dir));
        else:
            throw new \Exception("{$dir} is not a valid directory.");
        endif;
    }
    
    public function start()
    {
        $this->router->start();
        if($this->router->routeNotFound)
        {
            header("{$this->router->getRequest()->serverProtocol} 404 Not Found");
            // $data = array(
            //     'title'=>'404 Page Not Found',
            //     'styles' => array('style'));
        }
        // echo ob_get_clean();
    }
    
}

