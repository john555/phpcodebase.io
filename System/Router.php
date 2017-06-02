<?php

namespace System;

use \System\Http\Request;
use \System\Http\Response;

class Router
{
    public $requestHandler;
    public $responseHandler;
    private $route; // eg john/dashboard
    private $routePattern; // eg :username/dashboard
    private $callback; // function to execute when matching route is found
    private $params = array(); // parameters to pass to callback
    private static $matchNotFound = true;
    private $methods = array('get', 'post', 'put', 'delete');
    public $routeNotFound = false;
    private static $routePrefix;
    private $authorize;
    private $signInManager;
    private $signInUrl = SIGN_IN_URL;
    
    function __construct(Request $requestHandler, Response $responseHandler)
    {
        $this->requestHandler = $requestHandler;
        $this->responseHandler = $responseHandler;
        $this->signInManager = new \System\Identity\SignInManager();
        $this->computeRoute();
    }

    function getRequest()
    {
        return $this->requestHandler;
    }

    public function __call($method, $params)
    {
        if(in_array($method, $this->methods)):
            if(isset($params[0]) && isset(self::$routePrefix)):
                $params[0] = '/' . trim(self::$routePrefix, '/') . '/' . ltrim($params[0], '/');
                
            endif;
            $param1 = array(strtoupper($method));
            $params = array_merge($param1, $params);
            
            call_user_func_array(array($this, 'route'), $params);
        else:
            throw new \Exception("Invalid method ".__CLASS__."::$method");
        endif;
    }

    public function start()
    {
        if(self::$matchNotFound === false): // a match was found
            $this->requestHandler->isAuthenticated = $this->signInManager->isAuthenticated();
            
            if($this->authorize && !($this->requestHandler->isAuthenticated)):
                // redirect to login
                $returnUrl = "{$this->requestHandler->requestScheme}://{$this->requestHandler->httpHost}";
                if($this->requestHandler->serverPort !== "80")
                    $returnUrl = trim($returnUrl, "/"). "/:{$this->requestHandler->serverPort}";
                $returnUrl .= "{$this->requestHandler->requestUri}";

                $returnUrl = urlencode($returnUrl);
                header("{$this->requestHandler->serverProtocol} 401 Unauthorized");
                header("Location: {$this->signInUrl}?return_url={$returnUrl}");
            endif;

            $this->requestHandler->params = $this->params;
            call_user_func_array($this->callback, array($this->requestHandler, $this->responseHandler));
            
        else: // match was not found. return 404 response
            $this->routeNotFound = true;
        endif;
    }

    function usePrefix($prefix)
    {
        self::$routePrefix = $prefix;
    }

    function dropPrefix()
    {
        self::$routePrefix = null;
    }

    /**
    * $request_type = 'GET', 'POST', 'PUT', 'DELETE', ...
    */
    private function route($request_type, $pattern, $callback, bool $authorize = false)
    {
        if($this->requestHandler->requestMethod !== strtoupper($request_type)) return;
        
        if(self::$matchNotFound):
            if($this->isRoute(trim($pattern, '/'))):
                $this->computeRouteParams();
                self::$matchNotFound = false;
                $this->authorize = $authorize;
                $this->callback = $callback;
            endif;
        endif;
    }

    private function isRoute($pattern)
    {
        $raw_route = trim(str_replace("?{$this->requestHandler->queryString}", "", $this->requestHandler->requestUri), "/");
        $this->routePattern = strtolower($pattern);

        if($pattern === $this->route) // direct route
            return true;
        else
            return $this->isIndirectRoute();
        return false;
    }

    private function isIndirectRoute()
    {
        $routes = explode('/', $this->route);
        $r_size = count($routes);
        $patterns = explode( '/', $this->routePattern);
        $p_size = count($patterns);
        $is_match = false;

        if($r_size === $p_size):
            for($i = 0; $i < $r_size; $i++):
                if($routes[$i] === $patterns[$i]):
                    $is_match = true;
                else:
                    if(preg_match('/:/', $patterns[$i])): // conttains ':' at the beginning of string
                        $is_match = true;
                    else:
                        return false;
                    endif;
                endif;
            endfor;

            return $is_match;
        endif;
        return false;
    }

    private function computeRouteParams()
    {
        $str1_arr = explode('/', $this->routePattern);
        $str2_arr = explode('/', $this->route);
        $size1 = count($str1_arr);
        $size2 = count($str2_arr);

        if($size1 === $size2)
        {
            for($i = 0; $i < $size1; $i++)
            {
                if($str1_arr[$i] !== $str2_arr[$i]):
                    $this->params[ltrim($str1_arr[$i], ':')] = urldecode($str2_arr[$i]);
                endif;
            }
        }
    }

    private function computeRoute()
    {
        $route = trim(str_replace("?{$this->requestHandler->queryString}", "", $this->requestHandler->requestUri), "/");
        $str1 = trim($route, '/');
        $str2 = trim($this->requestHandler->scriptName, '/');
        $str1_arr = explode('/', $str1);
        $str2_arr = explode('/', $str2);
        $size1 = count($str1_arr);
        $size2 = count($str2_arr);
        $arr = array();

        for($i = 0; $i < $size1 && $i !== $size2 - 1; $i++)
        {
            if($str2_arr[$i] === $str1_arr[$i])
                array_push($arr, $str1_arr[$i]);
            else continue;
        }

        $this->route = strtolower(implode('/', array_diff($str1_arr, $arr)));
    }
}
