<?php
namespace System\Identity;

class User extends \System\Data\Store
{
    public $id;
    public $first_name;
    public $last_name;
    public $username;
    public $email;
    public $email_confirmed;
    public $password_hash;
    public $phone_number;
    public $phone_number_confirmed;
    public $is_active;
    public $timestamp;
    public $lockout_enabled;
    public $lockout_end_date;
    public $access_failed_count;

    public function __construct()
    {
        parent::__construct();
    }

    
}

