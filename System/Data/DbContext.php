<?php

namespace System\Data;

abstract class DbContext
{
    private $db;
    private $dsl;

    // args example 'mysql://localhost/sample_db', 'sampler', 'sampler@localhost'
    function __construct($addr = DB_TYPE.'://'.DB_HOST.':'.DB_PORT.'/'.DB_NAME, $username = DB_USER, $password = DB_PASSWORD)
    {
        $this->connect($addr, $username, $password);
    }

    /**
    * @method Connect connects to the database
    * @param $addr: Specifys server's database type(mysql), hostname, table
    *   Allowed Format: [database_type]://[hostname]:[port]/[table_name]
    * @param $username: database username
    * @param $password: database password
    * @return void
    */
    private function connect($addr, $username, $password)
    {
        $this->parseAddr($addr);
        try
        {
            $this->db = new \PDO($this->dsl, $username, $password);
            if($this->db->getAttribute(\PDO::ATTR_ERRMODE) !== \PDO::ERRMODE_EXCEPTION)
            {
                $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }
        } 
        catch (\PDOException $e)
        {
            throw $e;
        }
        
    }

    /**
    * Parses server's address information
    * @param $addr: Specifys server's database type(mysql), hostname, table
    *   Allowed Format: [database_type]://[hostname]:[port]/[table_name]
    */
    private function parseAddr($addr) 
    {
        $explode1 = explode('://', $addr);
            $type = $explode1[0];
        $explode2 = explode('/', $explode1[1]);
            $db = $explode2[1];
        $explode3 = explode(':', $explode2[0]);
            $host = $explode3[0];
            $port = (count($explode3) == 2)? $explode3[1] : '';
        $this->dsl = "$type:host=$host;port=$port;dbname=$db";
    }
    
    /**
    * execute a query
    */
    public function query($sql, array $tokens = array())
    {
        try
        {
            $stmt = $this->db->prepare($sql);
            foreach($tokens as $key => $value):
                if(is_int($value)):
                    $stmt->bindValue($key, $value, \PDO::PARAM_INT);
                else:
                    $stmt->bindValue($key, $value);
                endif;
            endforeach;

            $result = $stmt->execute();

            if(preg_match('/SELECT/', $sql)):
                $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                return $data;
            endif;

            $stmt->closeCursor();
            return $result;
        }
        catch(\PDOExceptio $e)
        {
            throw $e;
        }
    }

    public function guid($len = 10)
    {
        $id = rand(1, 1);
        --$len;

        for($i = 0; $i < $len; ++$i)
            $id .= rand(0, 9);

        return $id;
    }
    
    function __destruct()
    {
        $this->db = null;
    }
}