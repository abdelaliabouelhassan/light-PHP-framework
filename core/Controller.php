<?php

namespace App\core;
use App\core\Application;


class Controller
{

    public string $layout = 'main';

    /*
        * @var view accept view file name 
    */
    public function render($view,$data = []){
        return Application::$app->router->runderView($view,$data);
    }


    public function setLayout($layout){
        $this->layout = $layout;
    }


    public function getLayout(){
        return $this->layout;
    }

   
}