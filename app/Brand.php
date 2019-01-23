<?php

namespace App;
use App\Brand;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
  protected $table = "brands";
  protected $fillable = ["name","banner","thumbnail","epin_brand","client_id"];

  public function getFillable(){
    return $this->fillable;
  }
  public function client()
  {
    return $this->hasMany(Client::class, 'client_id');
  }
  public function template_denominations()
  {
    return $this->belongsToMany(TemplateDenomination::class, 'id');
  }
  public function template_categories()
  {
    return $this->belongsToMany(TemplateCategory::class, 'id');
  }
}
