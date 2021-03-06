<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = ['full_name', 'email', 'text','apartment_id'];

    public function apartment(){
        return $this->belongsTo('App\Model\Apartment');
    }
}
