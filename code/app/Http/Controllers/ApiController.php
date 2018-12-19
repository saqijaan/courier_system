<?php

namespace App\Http\Controllers;

/**
 * Models Imporing
 */
use App\City;
use App\User;
use App\Price;
use App\Branch;
use App\Shipment;
use Carbon\Carbon;
/**
 * General Class Import Necessory for Controller
 */
use App\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use Auth;
class ApiController extends Controller
{
    
    /**
     * Login Existing user
     */

    public function user(){
        return auth()->user();
    }
    public function login(Request $request){
        $rules = [
            'email'     => 'required',
            'password'  => 'required'
        ]; 
        $this->_validate($request,$rules);
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')],false,false)) {
            // Authentication passed...
            // $user = auth()->user();

            $user = Auth::guard('web')->getLastAttempted(); 

            $date   = Carbon::parse($user->updated_at);
            $now    = Carbon::now();

            if ($date->diffInDays($now) > 1){
                $user->api_token = str_random(30).'-'.base64_encode(microtime());
                $user->save();
            }
            self::_response(false,'',$user->toJson());
        }
        self::_response(true,"Invalid Username or Password");
    }

    /*************************************************
     *                  User
     ************************************************
     */

    /**
     * Register New User Here
     */
    public function registerUser(Request $request){
        \Log::info(\request());
        $data   =     $request;
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'name'      =>  'required',
            'phone'     =>  'required|max:13|min:10',
            'email'     =>  'required|email|unique:users,email',
            'address'   =>  'required',
            'password'  =>  'required',
            'city_id'   =>  'required',
        ];

        $this->_validate($request,$rules);

        if ( User::create([
            'name' => $data->name,
            'phone'=> $data->phone,
            'email' => $data->email,
            'address'=>$data->address,
            'city_id'=>$data->city_id,
            'password' => \Hash::make($data->password),
            'api_token' => str_random(30).'-'.base64_encode(microtime())
        ]))
            self::_response(false,'',"User Registered");
        else
            self::_response(true,"User Registeration Error");

    }

    /**
     * Update User Here
     */
    private function _updateUser($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'name'      =>  'required',
            'phone'     =>  'required|max:13|min:10',
            'email'     =>  'required|email|unique:users,email,'.auth()->Id().'',
            'address'   =>  'required',
            'city_id'   =>  'required',
        ];

        $this->_validate($request,$rules);

        $user           = User::find(auth()->Id())->first();
        $user->name     = $data->name;
        $user->phone    = $data->phone;
        $user->email    = $data->email;
        $user->address  = $data->address;
        $user->city_id  = $data->city_id;
        $user->password = $data->password !=null ? \Hash::make($data->password): $user->password;
        $user->save();
        self::_response(false,"User Updated");
    }

    /**
     * Delete User
     */
    private function _delUser($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'      =>  'required|integer',
        ];

        $this->_validate($request,$rules);
        User::find($data->id)->delete();
        self::_response(false,"User Deleted Successfully");
    }

    public function users(){
        self::_response(false,'',User::with('city')->get()->toJson());
    }
    /***************************************************
     *                City
     * *************************************************
     */
    /**
     * Add New City
     */
    private function _addCity($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'name'      =>  'required|unique:cities,name',
        ];

        $this->_validate($request,$rules);

        $city   =   new City;
        $city->name = $data->name;
        $city->save();

        self::_response(false,"City Saved Successfully");
    }

    /**
     * Delete City
     */
    private function _delCity($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'      =>  'required|integer',
        ];

        $this->_validate($request,$rules);
        City::find($data->id)->delete();
        self::_response(false,"City Deleted Successfully");
    }

    /**
     * get Citiese
     */
    public function getcitiese(){
        self::_response(false,'',City::all()->toJson());
    }

    /****************************************************
     *              Price
     * **************************************************
     */
    /**
     * Save New Price Information
     */
    private function _savePrice(){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'price'             =>  'required|double',
            'description'       =>  'required',
        ];

        $this->_validate($request,$rules);
        $price = new Price;
        $price->description     = $data->description;
        $price->price           = $data->price;
        $price->save();
        self::_response(false,"Price Saved Successfully");

    }
    /**
     * Save New Price Information
     */
    private function _updatePrice(){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'                =>  'required|integer',
            'price'             =>  'required|double',
            'description'       =>  'required',
        ];

        $this->_validate($request,$rules);
        $price = Price::find($data->id);
        $price->description     = $data->description;
        $price->price           = $data->price;
        $price->save();
        self::_response(false,"Price Updated Successfully");

    }

    /**
     * Delete Price
     */
    private function _delPrice($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'                =>  'required|integer',
        ];

        $this->_validate($request,$rules);
        Price::find($data->id)->delete();
        self::_response(false,"Price Deleted Successfully");
    }

    /**
     * Price List
     */
    public function priceList(){
        self::_response(false,'',Price::all()->toJson());
    }
    /*****************************************************************
     *                      Shipment
     ****************************************************************
     */

    /**
     * Add New Shipment
     */
    public function _saveShipment($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [

            'sender_name'       =>  'required',
            'sender_phone'      =>  'required',
            'sender_address'    =>  'required',
            'sender_city'       =>  'required|integer',

            'receiver_name'     =>  'required',
            'receiver_phone'    =>  'required',
            'receiver_address'  =>  'required',
            'receiver_city'     =>  'required|integer',

            'weight'            =>  'required|numeric',
            'payment_type'      =>  'required',
            'price'             =>  'required|numeric',
            'pick_up_type'      =>  'required',

            'source_branch'     =>  'required|integer',
            'dest_branch'       =>  'required|integer',
        ];

        $this->_validate($request,$rules);
        $shipment = new Shipment;
        $shipment->user_id          =   auth()->user()->id;
        $shipment->sender_name      =   $data->sender_name;
        $shipment->sender_phone     =   $data->sender_phone;
        $shipment->sender_address   =   $data->sender_address;
        $shipment->sender_city      =   $data->sender_city;

        $shipment->receiver_name    =   $data->receiver_name;
        $shipment->receiver_phone   =   $data->receiver_phone;
        $shipment->receiver_address =   $data->receiver_address;
        $shipment->receiver_city    =   $data->receiver_city;

        $shipment->weight           =   $data->weight;
        $shipment->status           =   'Picked Up';

        $shipment->payment_type     =   $data->payment_type;
        $shipment->price            =   $data->price;
        $shipment->pick_up_type     =   $data->pick_up_type;
        $shipment->source_branch    =   $data->source_branch;
        $shipment->dest_branch      =   $data->dest_branch;
        $shipment->save();
        self::_response(false,'Shipment Created Successfully');
    }

    /**
     * Update Shipment
     */
    public function _updateShipment($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'                =>  'required|integer|exists:shipments,id',
            'sender_name'       =>  'required',
            'sender_phone'      =>  'required',
            'sender_address'    =>  'required',
            'sender_city'       =>  'required|integer',

            'receiver_name'     =>  'required',
            'receiver_phone'    =>  'required',
            'receiver_address'  =>  'required',
            'receiver_city'     =>  'required|integer',

            'weight'            =>  'required|numeric',
            'payment_type'      =>  'required',
            'price'             =>  'required|numeric',
            'pick_up_type'      =>  'required',
            'source_branch'     =>  'required|integer',
            'dest_branch'       =>  'required|integer',
        ];

        $this->_validate($request,$rules);
        $shipment = Shipment::find($data->id);
        $shipment->sender_name      =   $data->sender_name;
        $shipment->sender_phone     =   $data->sender_phone;
        $shipment->sender_address   =   $data->sender_address;
        $shipment->sender_city      =   $data->sender_city;

        $shipment->receiver_name    =   $data->receiver_name;
        $shipment->receiver_phone   =   $data->receiver_phone;
        $shipment->receiver_address =   $data->receiver_address;
        $shipment->receiver_city    =   $data->receiver_city;

        $shipment->weight           =   $data->weight;
        $shipment->status           =   'Picked Up';

        $shipment->payment_type     =   $data->payment_type;
        $shipment->price            =   $data->price;
        $shipment->pick_up_type     =   $data->pick_up_type;
        $shipment->source_branch    =   $data->source_branch;
        $shipment->dest_branch      =   $data->dest_branch;
        $shipment->save();
        self::_response(false,'Shipment Updated Successfully');
    }


    /**
     * Del Shipment
     */
    public function _delShipment($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'                =>  'required|integer',
        ];

        $this->_validate($request,$rules);
        $deleted    =   Shipment::where('id',$data->id)->where('status','Picked Up')->first();
        $msg    =   $deleted ? $deleted->delete()."Shipment Deleted Successfully" : "Shipment cannot be Deleted";
        self::_response(false,$msg);
    }

    /**
     * Schedule Shipemnt
     */
    public function _scheduleShipment($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'                =>  'required|integer|exists:shipments,id',
        ];

        $this->_validate($request,$rules);
        $shipment = Shipment::where($data->id)->where('status','Picked Up')->first();
        if ($shipment){
            $shipment->status           =   'Scheduled';
            $shipment->save();
            $msg= "Shipment scheduled Successfully";
        }else{
            $msg= "Shipment cannot be scheduled";
        }
        self::_response(false,$msg);
    }

    /**
     * Shipemnt Arrived
     */
    public function _arrivedShipment($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'                =>  'required|integer|exists:shipments,id',
        ];

        $this->_validate($request,$rules);
        $shipment = Shipment::where($data->id)->where('status','Scheduled')->first();
        if ($shipment){
            $shipment->status           =   'Arrived';
            $shipment->save();
            $msg= "Shipment Arrived";
        }else{
            $msg= "Shipment cannot be updated";
        }
        self::_response(false,$msg);
    }

    /**
     * Shipemnt Arrived
     */
    public function _scheduleFDShipment($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'                =>  'required|integer|exists:shipments,id',
        ];

        $this->_validate($request,$rules);
        $shipment = Shipment::where($data->id)->where('status','Arrived')->first();
        if ($shipment){
            $shipment->status           =   'Scheduled for Delivery';
            $shipment->save();
            $msg= "Shipment scheduled";
        }else{
            $msg= "Shipment cannot be updated It is not arrived";
        }
        self::_response(false,$msg);
    }

    /**
     * Shipemnt Delivered
     */
    public function _deliveredShipment($request){
        $data   =     json_decode($request->api_data);
        if ($data==null ) self::_response(true,'Invalid Request');
        $rules= [
            'id'                =>  'required|integer|exists:shipments,id',
        ];

        $this->_validate($request,$rules);
        $shipment = Shipment::where($data->id)->where('status','Scheduled for Delivery')->first();
        if ($shipment){
            $shipment->status           =   'Delivered';
            $shipment->save();
            $msg= "Shipment Delivered";
        }else{
            $msg= "Shipment cannot be updated";
        }
        self::_response(false,$msg);
    }

    /**
     * Shipments 
     */
    public function shipmentList(){
        self::_response(false,'',Shipment::with('send_city','rec_city')->get()->toJson());
    }
    /***************************************************
     *                Profile
     * *************************************************
     */
    /**
     * Get Profile
     */
    private function _profile($request){
        self::_response(false,auth()->user());
    }

    /***************************************************
     *               Helper Functions
     * *************************************************
     */

    /**
     * Validate Request With Given Rules
     */
    private function _validate($request,$rules){
        $validator          =   \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            self::_response(true,$validator->messages());
        }
    }

    /**
     * Return Json Response with Error Code and Data
     */
    public function _response($IsError,$msg,$data=''){
        if (empty($data)){
            $data = "{}";
        }
        $response = new ApiResponse;
        $response->error    = $IsError;
        $response->msg       = "".$msg;
        $response->data     = $data;
        $response->Response();
    }
}
