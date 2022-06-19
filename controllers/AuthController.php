<?php
namespace App\controllers;

use App\core\Controller;
use App\core\Request;
use App\models\User;

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
            return $request->all();
           
        }
    }



    public function register(Request $request)
    {
        
        if ($request->isGet()) {
            return $this->render('Auth/register');
        } else {
            $request->validate([
                'name' => ['required', 'min:3', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'min:3', 'max:255','confirmed'],
            ]);
           
          
          
        }
    }
}
