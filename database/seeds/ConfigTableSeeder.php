<?php

use Illuminate\Database\Seeder;
use App\Config;
class ConfigTableSeeder extends Seeder
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
          'attribute'				   	=> 'Convenience Fee',
          'value'		      	    => '50.00',
          'client_id'		      	=> 1
        ],
        [
          'attribute'				   	=> 'Access Token',
          'value'		      	    => 'EAAYE0ZAAwZAZBUBAHflqStDmB0ZBODx0ABWo2Io0M0ZCc32ThYHaRYqNIXcGfzrDZABUC1npwDBnzdAo78BaIZCoSyZBzKrS3BMg3uW9hB0VV1yV1KGgje977TPBUeOv2UXVRibH46jzLK7wQh3Nfo92JdWWHxfrCav6UWRU510D26g3eE7QdMBP',
          'client_id'		      	=> 1
        ],
        [
          'attribute'				   	=> 'Bot Name',
          'value'		      	    => 'MercuryGiftCards',
          'client_id'		      	=> 1
        ],
      ];
      foreach ($data as $key)
      {
        Config::create([
          'attribute'         => $key['attribute'],
          'value'             => $key['value'],
          'client_id'         => $key['client_id'],
        ]);
      }
    }
}
