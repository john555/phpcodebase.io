<?php
namespace System\Http;

class Response
{
    private $viewDir;

    public function __construct()
    {

    }

    public function setViewDir($dir)
    {
        if(is_dir($dir)):
            $this->viewDir = $dir;
        else:
            throw new \Exception("{$dir} is not a valid directory.");
            
        endif;
    }

    public function render($view, $data = array())
    {
        $file = preg_replace('/\//', DS, $this->viewDir.DS.$view.".php");
        if(is_file($file)):
            
            foreach($data as $key => $val):
                $$key = $val;
            endforeach;

            include_once $file;
        else:
            throw new \Exception("The page you are trying to render does not exist. File: ".$file);
        endif;
    }
}