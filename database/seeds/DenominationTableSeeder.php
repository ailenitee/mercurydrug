<?php

use Illuminate\Database\Seeder;
use App\Denomination;
class DenominationTableSeeder extends Seeder
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
          'amount'					=> '100',
          'text'		      	=> 'One Hundred Pesos'
        ],
        [
          'amount'					=> '200',
          'text'			      => 'Two Hundred Pesos'
        ],
        [
          'amount'					=> '500',
          'text'		      	=> 'Five Hundred Pesos'
        ],
        [
          'amount'					=> '1000',
          'text'		      	=> 'One Thousand Pesos'
        ],
        [
          'amount'					=> '2000',
          'text'		      	=> 'Two Thousand Pesos'
        ]
      ];
      foreach ($data as $key)
      {
        Denomination::create([
          'amount'         => $key['amount'],
          'text'    => $key['text'],
        ]);
      }
    }
}
