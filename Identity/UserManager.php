<?php
namespace System\Identity;

class UserManager extends \System\Data\Store
{
    private $user;

    function __construct()
    {
        parent::__construct();
        $this->tableName = 'user';
    }

    function createUser(\System\Identity\User $user, $password)
    {
        $user->id = $user->guid();
        $user->password_hash = password_hash($password, PASSWORD_DEFAULT, array("cost" => 12) );
        if(is_null($user->username)):
            $user->username = $user->email;
        endif;
        $data = $user->get_data();
        
        $this->tableName = $user->tableName;
        return $this->save($data);
    }

    function signIn(\System\Identity\User $user, $password)
    {
        $this->tableName = $user->tableName;
        $data = $user->get_data();
        $email = $data['email'];
        $results = $this->findByColumn('email', $email);

        if($results && count($results) > 0):
            $hash = $results[0]['password_hash'];
            return password_verify($password, $hash);
        endif;
        return false;
    }

    function emailSignIn(string $email, string $password)
    {
        $results = $this->findByColumn('email', $email);

        if($results && count($results) > 0):
            $hash = $results[0]['password_hash'];
            return password_verify($password, $hash);
        endif;
        return false;
    }

    function usernameSignIn(string $username, string $password)
    {
        $results = $this->findByColumn('username', $username);

        if($results && count($results) > 0):
            $hash = $results[0]['password_hash'];
            return password_verify($password, $hash);
        endif;
        return false;
    }

    function findById($id)
    {
        $user_data = $this->findByColumn('id', $id, 1);
        return $this->constructUserObject($user_data[0]);
    }

    private function constructUserObject($data)
    {
        $user = new User;

        foreach($data as $key => $value):
            $user->$key = $value;
        endforeach;

        return $user;
    }
}