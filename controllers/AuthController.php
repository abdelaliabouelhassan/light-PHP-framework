<?php
namespace App\controllers;

use App\core\Controller;
use App\core\Request;

class AuthController extends Controller
{
  
    //contructor
    public function __construct()
    {
        $this->setLayout('auth');
    }

    public function login(Request $request)
    {
       
        if ($request->isGet()) {
            return $this->render('Auth/login');
        } else {
            $body = $request->getBody();
            echo '<pre>';
            print_r($body);
            echo '</pre>';
            exit;
            return "data submited";
        }
    }



    public function register(Request $request)
    {
        if ($request->isGet()) {
            return $this->render('Auth/register');
        } else {
            $body = $request->getBody();
            echo '<pre>';
            print_r($body);
            echo '</pre>';
            exit;
            return "data submited";
        }
    }
}
