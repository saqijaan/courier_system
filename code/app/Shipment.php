<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    public function send_city(){
        return $this->belongsTo(City::class,'sender_city');
    }
    public function rec_city(){
        return $this->belongsTo(City::class,'receiver_city');
    }
}
