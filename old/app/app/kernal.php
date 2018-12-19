<?php

namespace App;

/**
 * General Classes Required To Start Kernal
 * and Manage Responses
 */

use App\Helpers\DB;
use App\Helpers\Request;
use App\Models\Auth;
use App\Models\ApiResponse;

/**
 * Model Classes To Manipulate Database
 */

use App\Models\User;


class kernal{

    public function handle($request){
        $request    =   new Request($_POST);
        if (!method_exists(new kernal,$request->api_method())){
            $response = new ApiResponse;
            $response->isError  = true;
            $response->Data     = "Invalid Request: ".$request->api_method();
            $response->Response();
        }
        self::{$request->api_method()}();
    }

    /**
     * Login Existing User
     */
    private function login(){
        $response = new ApiResponse;
        $response ->isError = false;
        $response ->Data    = "Logged In".json_encode($_POST);
        $response->Response();
    }

    /**
     * Register New User
     */
    private function register(){
        $user =new User;
        $user->name     = "Muhammad Saqib Ali";
        $user->phone    = "03016530506";
        $user->email    = "saqiba874@gmail.com";
        $user->address  = "Sahiwal 121212";
        $user->city_id  = "1";
        $user->password = "laskdhflkasdhflasd";
        $user->token    = "alskdflkasdfhklasdf";
        if ($user->save()){
            $response = new ApiResponse;
            $response ->isError = false;
            $response ->Data    = "Data Saved".json_encode($_POST);
            $response->Response();
        }else{
            $response = new ApiResponse;
            $response ->isError = true;
            $response ->Data    = "Save Error".json_encode($_POST);
            $response->Response();
        }
    }

    /**
     * Places A New Shipment
     */
    private function newShipment(){

    }
}