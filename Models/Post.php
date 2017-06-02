<?php
namespace Models;

class Post extends \System\Data\Store
{
    public $id;
    public $message;

    public function __construct()
    {
        parent::__construct();
    }
    
}