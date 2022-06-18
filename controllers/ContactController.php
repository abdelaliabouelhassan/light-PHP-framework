<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Request;

class ContactController extends Controller
{
    public function index()
    {
        
      return $this->render('contact',[
          'name' => 'the master'
      ]);
    }

    public function store(Request $request){
     $body = $request->getBody();
     echo '<pre>';
        print_r($body);
        echo '</pre>';
        exit;
        return "data submited";
    }
}