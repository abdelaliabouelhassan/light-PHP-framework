<?php

namespace App\core;

class DataBase {

        private $host;
        private $dbname;
        private $user;
        private $password;
        private $pdo;

     
        public function __construct()
        {
            $this->host = 'localhost';
            $this->dbname = 'boystack';
            $this->user = 'root';
            $this->password = '';
            $this->pdo = new \PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->password);
        }


        public function prepare($sql)
        {
            return $this->pdo->prepare($sql);
        }
        
      

        public function getPdo()
        {
            return $this->pdo;
        }

        public function query($sql, $params = [])
        {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        }

        public function lastInsertId()
        {
            return $this->pdo->lastInsertId();
        }

        public function beginTransaction()
        {
            $this->pdo->beginTransaction();
        }

        public function commit()
        {
            $this->pdo->commit();
        }

        public function rollBack()
        {
            $this->pdo->rollBack();
        }

        public function quote($string)
        {
            return $this->pdo->quote($string);
        }

        public function exec($sql)
        {
            return $this->pdo->exec($sql);
        }

        public function lastError()
        {
            return $this->pdo->errorInfo();
        }

        public function lastErrorCode()
        {
            return $this->pdo->errorCode();
        }

}