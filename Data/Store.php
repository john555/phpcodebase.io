<?php
namespace System\Data;

abstract class Store extends DbContext
{
    protected  $tableName;

    function __construct()
    {
        parent::__construct();
        $this->tableName = $this->inferTableName();
    }

    public function save(array $data = null)
    {
        if(is_null($data)):
         $values = $this->getData();
        else:
            $values = $data;
        endif;
        
        $sql = "INSERT INTO {$this->tableName}(";
        $columns = "";
        $tokens = "";
        $token_values = array();
        foreach($values as $key => $value):
            $columns .= "{$key}, ";
            $tokens .= ":{$key}, ";
            $token_values[":".$key] = $value;
        endforeach;
        $sql .= rtrim($columns, ', ').") VALUES(".rtrim($tokens, ', ').")";
        
        return $this->query($sql, $token_values);
    }

    function findAll()
    {
        $sql = "SELECT * FROM {$this->tableName}";
        return $this->query($sql);
    }

    public function findByColumn($column, $value, $limit = 12)
    {
        $sql = "SELECT *
                FROM {$this->tableName}
                WHERE $column = :val
                LIMIT :limit";

        return $this->query($sql, array(
            ':val' => $value, 
            ':limit' => $limit
        ));
    }
    
    protected function inferTableName()
    {
        $arr = explode("\\", get_called_class());
        $len = count($arr);
        return  strtolower(($len > 0)? $arr[$len - 1] : $len[0]);
    }

    protected function getData()
    {
        $user_properties = get_object_vars($this);
        $data = array();
        foreach($user_properties as $key => $value):
            if(!is_null($value) and !in_array($key, array("tableName"))):
                $data[$key] = $value;
            endif;
        endforeach;
        
        return $data;
    }

}