<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function Users(){
        return $this->hasMany(User::class);
    }
    public function Shipments(){
        return $this->hasMany(Shipment::class);
    }
}
