<?php

namespace App;
use App\Logs;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

class Logs extends Model
{
    protected $table = "logs";
    protected $fillable = ["cart_id","image","code","verified_date","status"];

    public function getFillable(){
        return $this->fillable;
    }

}
