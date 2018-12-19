<?php

namespace App;

class ApiResponse
{
    public function Response(){
        \Log::info(json_encode($this));
        exit(json_encode($this));
    }
}
