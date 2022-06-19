<?php




namespace App\models;

use App\core\Model;

class User extends Model
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $created_at;
    public $updated_at;


   
}