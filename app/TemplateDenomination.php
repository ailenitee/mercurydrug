<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateDenomination extends Model
{
  protected $table = "template_denominations";
  protected $fillable = ["brand_id","denomination_id"];

  public function getFillable(){
      return $this->fillable;
  }
  public function brand()
  {
    return $this->hasMany(Brand::class, 'brand_id');
  }
  public function denomination()
  {
    return $this->hasMany(Denomination::class, 'denomination_id');
  }
}
