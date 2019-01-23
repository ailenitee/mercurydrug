<?php

namespace App;
use App\Transaction;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

class Transaction extends Model
{
    protected $table = "transactions";
    protected $fillable = ["cart_id","email","error_detail","reference_num","status"];

    public function getFillable(){
        return $this->fillable;
    }

}
