<?php

use App\core\Application;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/_global.php';
require __DIR__ . '/../router/web.php';
//$app->run();

$app->db->applayMigrations();

?>