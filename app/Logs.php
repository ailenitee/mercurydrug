<?php

namespace App;
use App\Logs;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

class Logs extends Model
{
    protected $table = "logs";
    protected $fillable = ["cart_id","id","epin","code"];

    public function getFillable(){
        return $this->fillable;
    }

}
