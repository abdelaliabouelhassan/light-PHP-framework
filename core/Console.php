<?php

namespace  App\core;

class Console
{
    public $commands = [];

    public function __construct()
    {
        $this->commands = [
            'help' => ' List of commands',
            'serve' => ' Start the application',
            'exit' => ' Exit the application',
            'migrate' => ' Run the migrations',
        ];

        $this->run();
    }

    public function run()
    {
        while (true) {
            $this->showCommands();
            $command = readline("Enter a command: ");
            $this->executeCommand($command);
        }
    }

    public function showCommands()
    {
        echo "Available commands: " . PHP_EOL;
        foreach ($this->commands as $key => $value) {
            echo $key . ': ' . $value . PHP_EOL;
        }
        echo "\n";
    }

    public function executeCommand($command)
    {
        if (array_key_exists($command, $this->commands)) {
            $this->$command();
        } else {
            echo "Command not found\n";
        }
    }

    public function help()
    {
        echo "Available commands: ";
        foreach ($this->commands as $key => $value) {
            echo $key . " ";
        }
        echo "\n";
    }

    public function exit()
    {
        echo "Bye!\n";
        exit();
    }


    public function serve()
    {
        echo "Serveing...\n";
        //execute commend php -S localhost:8000 -t public/

        shell_exec("php -S localhost:8000 -t public/");
    }

    public function migrate()
    {
        $db = new DataBase();
        $db->applayMigrations();
        exit();
    }
}
