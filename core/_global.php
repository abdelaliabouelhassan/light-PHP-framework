<?php

use App\core\Application;
use App\core\Session;

//global enstences variables
global $app;
$app = new Application();

function env($key)
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
    return $_ENV[$key] ?? '';
}
function get($url, $callback)
{
    global $app;
    $app->router->get($url, $callback);
}

function post($url, $callback)
{
    global $app;
    $app->router->post($url, $callback);
}

function consoleLog($message)
{
    echo $message . PHP_EOL;
}

function old($key)
{
    global $app;
    $data =  $app->session->get('flash_values_' . $key);
    $app->session->remove('flash_values_' . $key);
    
    return $data[$key] ?? '';
}

function errors()
{
    global $app;
    $errors =  $app->session->get('flash_errors');
    $app->session->remove('flash_errors');
    return $errors ?? [];
}
