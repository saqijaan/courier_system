<?php

namespace App\Helpers;

class request
{
    private $post;
    private $api_method;
    private $api_data;

    public function __construct(array $post){
        $this->post         = $post;
        $this->api_method   = isset($this->post['api_method'])? $this->post['api_method'] : false;
        $this->api_data     = isset($this->post['api_data'] ) ? $this->post['api_data']   : false;
    }
    public function api_method(){
        return $this->api_method;
    }
    public function api_data(){
        return $this->api_data;
    }
    public function isValid(){
        return  $this->api_method ? function_exists($this->api_method): false;
    }
}
