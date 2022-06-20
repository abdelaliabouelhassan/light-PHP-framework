<?php

namespace App\router;

use App\controllers\AuthController;
use App\controllers\ContactController;


get('/',[ContactController::class,'index']);
get('/contact',[ContactController::class,'index']);
post('/contact',[ContactController::class,'store']);
get('/login',[AuthController::class,'login']);
post('/login',[AuthController::class,'login']);
get('/register',[AuthController::class,'register']);
post('/register',[AuthController::class,'register']);





