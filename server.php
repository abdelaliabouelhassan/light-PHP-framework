<?php

//run the application

use App\core\Console;
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/_global.php';
$console = new Console();
$console->run();

?>