<?php
namespace System\Http;

class Request
{
    public $isAuthenticated = false;
    public $params;
    
    function __construct()
    {
        $this->gatherInfo();
    }

    private function gatherInfo()
    {
        foreach($_SERVER as $key => $val):
            $this->{$this->constNameToCamelCase($key)} = filter_var($val, FILTER_SANITIZE_SPECIAL_CHARS);
        endforeach;
    }

    public function getQueryString($key)
    {
        if(isset($_GET) && array_key_exists($key, $_GET))
        {
            return filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }   
        return false;
    }
    
    public function getBody() 
    {
        if(isset($_POST)):
            foreach($_POST as $key => $val)
            {
                if(filter_var($val, FILTER_VALIDATE_EMAIL)):
                    $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_EMAIL);
                else:
                    $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                endif;
            }
            return $data;
        endif;

        return false;
    }
    
    public function getFiles()
    {
        return isset($_FILES)? $_FILES : false;
    }

    public function getFile($key)
    {
         return (isset($_FILES) && array_key_exists($key, $_FILES))? $_FILES[$key] : false;
    }

    private function constNameToCamelCase($name)
    {
        $name = strtolower($name);
        
        preg_match_all('/_[a-z]/', $name, $matches);

        foreach($matches[0] as $match):
            $c = str_replace('_', '', strtoupper($match));
            $name = str_replace($match, $c, $name);
        endforeach;

        return $name;
    }
    
}