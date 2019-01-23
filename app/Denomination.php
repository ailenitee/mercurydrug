<?php

namespace App;
use App\Denomination;
use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
  protected $table = "denominations";
  protected $fillable = ["amount","text"];

  public function getFillable(){
      return $this->fillable;
  }
}
