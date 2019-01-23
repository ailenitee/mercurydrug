<?php

use Illuminate\Database\Seeder;
use App\Category;
class CategoryTableSeeder extends Seeder
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
          'name'				          => 'Birthday',
          'url'				            => 'https://s3.amazonaws.com/smgiftcard/images/giftcard/Birthday.jpg',
        ],
        [
          'name'				          => 'Christmas',
          'url'				            => 'https://s3.amazonaws.com/smgiftcard/images/giftcard/Christmas1.jpg'
        ],
        [
          'name'				          => 'Congratulations',
          'url'				            => 'https://s3.amazonaws.com/smgiftcard/images/giftcard/Congratulations.jpg'
        ],
        [
          'name'				          => 'For Her',
          'url'				            => 'https://s3.amazonaws.com/smgiftcard/images/giftcard/ForHer.jpg'
        ],
        [
          'name'				          => 'For Him',
          'url'				            => 'https://s3.amazonaws.com/smgiftcard/images/giftcard/ForHim.jpg'
        ],
        [
          'name'				          => 'Love',
          'url'				            => 'https://s3.amazonaws.com/smgiftcard/images/giftcard/Love.jpg'
        ],
        [
          'name'				          => 'Get Well Soon',
          'url'				            => 'https://s3.amazonaws.com/smgiftcard/images/giftcard/GetWellSoon.jpg'
        ],
        [
          'name'				          => 'Regular',
          'url'				            => 'Regular'
        ],
      ];
      foreach ($data as $key)
      {
        Category::create([
          'name'                => $key['name'],
          'url'                 => $key['url']
        ]);
      }
    }
}
