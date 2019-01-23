<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemplateCategory extends Model
{
  protected $table = "template_categories";
  protected $fillable = ["brand_id","category_id"];

  public function getFillable(){
      return $this->fillable;
  }
  public function brand()
  {
    return $this->hasMany(Brand::class, 'brand_id');
  }
  public function category()
  {
    return $this->hasMany(Category::class, 'category_id');
  }
}
