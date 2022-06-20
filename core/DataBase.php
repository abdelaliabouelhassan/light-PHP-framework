<?php

namespace App\core;

class DataBase
{

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




    public function createMigrationsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (
                id INT NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            )";
        $this->exec($sql);
    }

    public function getAppliedMigrations()
    {
        $sql = "SELECT name FROM migrations";
        $stmt = $this->query($sql);
        $migrations = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        return $migrations;
    }

    public function addMigration($name)
    {
        $sql = "INSERT INTO migrations (name) VALUES (:name)";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        if ($this->lastErrorCode() !== '00000') {
            consoleLog("Error: " . $this->lastError()[2]);
        } else {
            consoleLog("Migration {$name} applied at " . date('Y-m-d H:i:s'));
        }
    }

    public function removeMigration($name)
    {
        $sql = "DELETE FROM migrations WHERE name = :name";
        $stmt = $this->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        if ($this->lastErrorCode() !== '00000') {
            consoleLog("Error removing migration {$name}: " . $this->lastError());
        } else {
            consoleLog("Migration {$name} removed at " . date('Y-m-d H:i:s'));
        }
    }


    public function applayMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();
        $newMigrations = [];
        $migrations = scandir(Application::$DIR_ROOT . '/database/migrations');
        $migrations = array_filter($migrations, function ($item) {
            return strpos($item, '.php') !== false;
        });
        $migrations = array_map(function ($item) {
            return str_replace('.php', '', $item);
        }, $migrations);

        $migrations = array_diff($migrations, $appliedMigrations);

        foreach ($migrations as $migration) {
            //create table if not exists
            require_once Application::$DIR_ROOT . '/database/migrations/' . $migration . '.php';
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $class = new $className();
            $class->up();
            //add to applied migrations
            $this->addMigration($migration);
            $newMigrations[] = $migration;
        }

        if (count($newMigrations) == 0) {
            consoleLog('No new migrations');
        }
    }

    public function createTable($table, $columns)
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$table} (";
        foreach ($columns as $key => $column) {
            $sql .= $key . ' ' .  $column . ',';
        }
        $sql = rtrim($sql, ',');
        $sql .= ')';
        $this->exec($sql);

        if ($this->lastErrorCode() !== '00000') {
            consoleLog("Error creating table {$table}: " . $this->lastError());
        } else {
            consoleLog("Table {$table} created");
        }
      
    }

    public function dropTable($table)
    {
        $sql = "DROP TABLE IF EXISTS {$table}";
        $this->exec($sql);

        if ($this->lastErrorCode() !== '00000') {
            consoleLog("Error dropping table {$table}: " . $this->lastError());
        } else {
            consoleLog("Table {$table} dropped");
        }

    }

    public function addColumn($table, $column, $type)
    {
        $sql = "ALTER TABLE {$table} ADD {$column} {$type}";
        $this->exec($sql);

        if ($this->lastErrorCode() !== '00000') {
            consoleLog("Error adding column {$column} to table {$table}: " . $this->lastError());
        } else {
            consoleLog("Column {$column} added to table {$table}");
        }
    }

    public function dropColumn($table, $column)
    {
        $sql = "ALTER TABLE {$table} DROP COLUMN {$column}";
        $this->exec($sql);

        if ($this->lastErrorCode() !== '00000') {
            consoleLog("Error dropping column {$column} from table {$table}: " . $this->lastError());
        } else {
            consoleLog("Column {$column} dropped from table {$table}");
        }
    }

  
}
