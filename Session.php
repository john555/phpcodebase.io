<?php
namespace System;
/*
* Session handler
*/

class Session extends \System\Data\Store implements \SessionHandlerInterface
{
    protected $tableName = 'session';
    protected $idColumn = 'id';
    protected $dataColumn = 'data';
    protected $expiryColumn = 'expiry';
    protected $expiry;
    private $dumpStmt;

    function __construct() 
    {
        parent::__construct();
        $this->expiry = time() + (int) ini_get('session.gc_maxlifetime');
    }

    public function open($savePath, $name) 
    {
        return true;
    }

    public function read($sessId) 
    {
        $sql = "SELECT $this->dataColumn, $this->expiryColumn  FROM $this->tableName WHERE id=:id";
        
        $results = $this->query($sql, array(':id' => $sessId));
        if(!$results or count($results) < 1) return "";
        return $results[0][$this->expiryColumn] < time()? '' : $results[0][$this->dataColumn];
    }

    public function write($sessId, $sessData)
    {
        $sql = "INSERT INTO $this->tableName($this->idColumn, $this->dataColumn, $this->expiryColumn) 
                       VALUES(:id, :data, :expiry) 
                       ON DUPLICATE KEY 
                       UPDATE $this->dataColumn=:data, $this->expiryColumn=:expiry";
        return $this->query($sql, array(
            ':id' => $sessId,
            ':data' => $sessData,
            ':expiry' => $this->expiry
        ));
    }

    public function close() 
    {
        try
        {
            if($this->dumpStmt)
            {
                $this->dumpStmt->execute();
            }
            return true;
        }
        catch(PDOException $e)
        {
            throw $e;
        }
    }

    public function destroy($sessId) 
    {
        unset($_SESSION);

        if(isset($_COOKIE)):
            unset($_COOKIE);
        endif;
        
        try
        {
            return $sql->query($sql, array(
                ':id' => $sessId
            ));
        }
        catch(PDOException $e)
        {
            throw $e;
        }
    }

    public function gc($maxlife) 
    {
        $now = time();
        $sql = "DELETE FROM $this->tableName 
                WHERE $this->expiryColumn < :now";
                
        $this->collectGarbage = false;
        return $this->query($sql, array(
            ':now' => $now
        ));
    }
}
