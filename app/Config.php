<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
  protected $table = "configs";
  protected $fillable = ["attribute","value","client_id"];

  public function getFillable(){
      return $this->fillable;
  }
}
