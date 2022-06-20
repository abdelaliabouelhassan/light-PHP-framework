<?php

namespace App\core;

class DataBase {

        public $host;
        public $dbname;
        public $user;
        public $password;
        public $pdo;

     
        public function __construct()
        {
            $this->host = env('DB_HOST');
            $this->dbname = env('DB_NAME');
            $this->user = env('DB_USER');
            $this->password = env('DB_PASSWORD');
            $this->pdo = new \PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->password);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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


        public function applayMigrations()
        {
          $this->createMigrationsTable();
          $appliedMigrations =  $this->getAppliedMigrations();
            
          $newMigrations = [];
          $files = scandir(Application::$DIR_ROOT . '/database/migrations');
          $files = array_slice($files, 2); //remove . and ..
          $toAppliedMigrations = array_diff($files, $appliedMigrations); //get the difference between files and applied migrations
            foreach ($toAppliedMigrations as $migrations) {
                $this->applyMigration($migrations); // apply the migration
                $newMigrations[]  = $migrations;
            }

            echo "Applied migrations fuck: " . implode(', ', $toAppliedMigrations) . PHP_EOL;
            
            // if(!empty($newMigrations)) {
            //     $this->saveMigrations($newMigrations);
            // }else{
            //     echo 'No new migrations found ' . PHP_EOL;
            // }

        }

        public function createMigrationsTable()
        {
            $sql = "CREATE TABLE IF NOT EXISTS `migrations` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $this->exec($sql);
        }

        public function getAppliedMigrations()
        {
            $sql = "SELECT * FROM `migrations`";
            $stmt = $this->query($sql);
            $migrations = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $migrations;
        }

        public function applyMigration($migration)
        {
            require_once Application::$DIR_ROOT . '/database/migrations/' . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME); //get the filename without extension
            $instance = new $className();
            echo "Applying migration: " . $migration . PHP_EOL;
            $instance->up();
            echo "Migration applied: " . $migration . PHP_EOL;

        }


        public function saveMigrations(array $migrations)
        {
            $sql = "INSERT INTO `migrations` (`name`) VALUES (?)";
            $stmt = $this->prepare($sql);
            foreach ($migrations as $migration) {
                $stmt->execute([$migration]);
            }
        }
     

}