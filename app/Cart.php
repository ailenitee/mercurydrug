<?php

namespace App;
use App\Cart;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;

class Cart extends Model
{
    protected $table = "carts";
    protected $fillable = ["user_id","template_category_id","template_denomination_id","transaction_id","sender","name","quantity","address","email","mobile","message","total","pickup_date","fulfillment_type"];

    public function getFillable(){
        return $this->fillable;
    }

}
