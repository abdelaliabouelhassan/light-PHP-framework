<?php
use App\core\Application;

//global enstences variables
global $app;
$app = new Application();

function env($key)
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    return $_ENV[$key] ?? '';
}
function get($url,$callback){
    global $app;
    $app->router->get($url,$callback);
}

function post($url,$callback){
    global $app;
    $app->router->post($url,$callback);
}


?>