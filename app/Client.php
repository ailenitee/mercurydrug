<?php

namespace App;
use App\Client;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  protected $table = "clients";
  protected $fillable = ['convenience_fee','name','bot_name','access_token'];

  public function getFillable(){
    return $this->fillable;
  }
  public function brand()
  {
    return $this->belongsToMany(Brand::class, 'id');
  }
}
