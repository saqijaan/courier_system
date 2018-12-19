<?php 

namespace App\Models;

class Auth 
{
    public $token;
    public function __construct($token){
        $this->token=$token;
    }

    public function check(){
        return true;
    }
}
