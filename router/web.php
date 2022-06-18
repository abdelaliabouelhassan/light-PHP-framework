<?php

namespace App\router;

use App\controllers\AuthController;
use App\controllers\ContactController;
use App\core\Application;


$app = new Application();

$app->router->get('/',[ContactController::class,'index']);

$app->router->get('/contact',[ContactController::class,'index']);

$app->router->post('/contact',[ContactController::class,'store']);
$app->router->get('/login',[AuthController::class,'login']);
$app->router->post('/login',[AuthController::class,'login']);
$app->router->get('/register',[AuthController::class,'register']);
$app->router->post('/register',[AuthController::class,'register']);
$app->run();

