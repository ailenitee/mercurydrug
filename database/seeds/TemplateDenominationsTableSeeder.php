<?php

use Illuminate\Database\Seeder;
use App\TemplateDenomination;
class TemplateDenominationsTableSeeder extends Seeder
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
          'denomination_id'       => '1',
          'client_id'       => '1'
        ],
        [
          'brand_id'			    => '1',
          'denomination_id'       => '2',
          'client_id'       => '1'
        ],
        [
          'brand_id'			    => '1',
          'denomination_id'       => '3',
          'client_id'       => '1'
        ]
      ];
      foreach ($data as $key)
      {
        TemplateDenomination::create([
          'denomination_id'         => $key['denomination_id'],
          'brand_id'                => $key['brand_id'],
          'client_id'                => $key['client_id']
        ]);
      }
    }
}
