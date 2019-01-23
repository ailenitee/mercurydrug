3<?php

use Illuminate\Database\Seeder;
use App\User;
class DatabaseSeeder extends Seeder
{
  /**
  * Seed the application's database.
  *
  * @return void
  */
  public function run()
  {
    $data = [
      [
        'name'				=> 'superadmin',
        'email'					=> 'superadmin@glyphgames.com',
        'password'				=> bcrypt('1q2w3e4R'),
        'mobile'					=> '0000',
        'role_id'						=> 1,
        'status'				    => '1',
        'client_id'				=> 1
      ],
      [
        'name'				=> 'admin',
        'email'					=> 'admin@glyphgames.com',
        'password'				=> bcrypt('admin'),
        'mobile'					=> '0000',
        'role_id'						=> 1,
        'status'				    => '1',
        'client_id'				=> 1
      ]
    ];
    $this->call(RoleTableSeeder::class);
    $this->call(ClientTableSeeder::class);
    $this->call(BrandTableSeeder::class);
    $this->call(DenominationTableSeeder::class);
    $this->call(CategoryTableSeeder::class);
    $this->call(TemplateCategoriesTableSeeder::class);
    $this->call(TemplateDenominationsTableSeeder::class);
    $this->call(ConfigTableSeeder::class);

    foreach ($data as $key)
    {
      User::create([
        'name'          => $key['name'],
        'email'         => $key['email'],
        'password'      => $key['password'],
        'mobile'		    => $key['mobile'],
        'role_id'			    => $key['role_id'],
        'status'			  => $key['status'],
        'client_id'			  => $key['client_id']
      ]);
    }
  }
}
