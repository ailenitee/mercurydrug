<?php

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
    $this->call(BrandTableSeeder::class);
    $this->call(DenominationTableSeeder::class);
    $this->call(CategoryTableSeeder::class);
    $this->call(ThemesTableSeeder::class);
    $this->call(ClientTableSeeder::class);
  }
}
