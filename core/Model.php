<?php

namespace App\core;

abstract class Model
{
    public $id;
    public $created_at;
    public $updated_at;

    public function __construct($id = null)
    {
        $this->id = $id;
    }


    public function save()
    {
        $this->updated_at = date('Y-m-d H:i:s');
        if ($this->id === null) {
            $this->created_at = $this->updated_at;
            $this->id = $this->insert();
        } else {
            $this->update();
        }
    }

    protected function insert()
    {
        $sql = 'INSERT INTO ' . static::tableName() . ' (created_at, updated_at) VALUES (:created_at, :updated_at)';
        $stmt = Application::$app->db->prepare($sql);
        $stmt->bindValue(':created_at', $this->created_at);
        $stmt->bindValue(':updated_at', $this->updated_at);
        $stmt->execute();
        return Application::$app->db->lastInsertId();
    }

    
    protected function update()
    {
        $sql = 'UPDATE ' . static::tableName() . ' SET updated_at = :updated_at WHERE id = :id';
        $stmt = Application::$app->db->prepare($sql);
        $stmt->bindValue(':updated_at', $this->updated_at);
        $stmt->bindValue(':id', $this->id);
        $stmt->execute();
    }

    public static function All()
    {
        $sql = 'SELECT * FROM ' . static::tableName();
        $stmt = Application::$app->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
        return $result;
    }

    public static function find($id)
    {
        $sql = 'SELECT * FROM ' . static::tableName() . ' WHERE id = :id';
        $stmt = Application::$app->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchObject(static::class);
        return $result;
    }

    public static function findBy($column, $value)
    {
        $sql = 'SELECT * FROM ' . static::tableName() . ' WHERE ' . $column . ' = :value';
        $stmt = Application::$app->db->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        $result = $stmt->fetchObject(static::class);
        return $result;
    }

    public static function findByEmail($value)
    {
        
        return static::findBy('email', $value);
    }


    public static function tableName()
    {
        return strtolower(static::class) . 's';
    }





    

}


?>