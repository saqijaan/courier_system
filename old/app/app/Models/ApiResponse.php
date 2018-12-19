<?php 
namespace App\Models;

class ApiResponse
{
    public function Response(){
        exit(json_encode($this));
    }
}
