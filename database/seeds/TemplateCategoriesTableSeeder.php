<?php

use Illuminate\Database\Seeder;
use App\TemplateCategory;
class TemplateCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $data = [
        [
          'brand_id'			    => '1',
          'category_id'       => '1',
          'client_id'       => '1'
        ],
        [
          'brand_id'			    => '1',
          'category_id'       => '2',
          'client_id'       => '1'
        ]
      ];
      foreach ($data as $key)
      {
        TemplateCategory::create([
          'category_id'         => $key['category_id'],
          'brand_id'            => $key['brand_id'],
          'client_id'            => $key['client_id'],
        ]);
      }
    }
}
