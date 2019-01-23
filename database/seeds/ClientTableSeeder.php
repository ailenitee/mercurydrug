<?php

use Illuminate\Database\Seeder;
use App\Client;
class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // TODO: other clients
      $data = [
        [
          'name' => 'Mercury Drug'
        ]
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => 'Mercury Drug',
        //   'bot_name'				                => 'MercuryGiftCards',
        //   'access_token'		                => 'EAAYE0ZAAwZAZBUBAHflqStDmB0ZBODx0ABWo2Io0M0ZCc32ThYHaRYqNIXcGfzrDZABUC1npwDBnzdAo78BaIZCoSyZBzKrS3BMg3uW9hB0VV1yV1KGgje977TPBUeOv2UXVRibH46jzLK7wQh3Nfo92JdWWHxfrCav6UWRU510D26g3eE7QdMBP'
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => 'Max Restaurants',
        //   'bot_name'				                => 'MaxGiftCards',
        //   'access_token'		                => 'EAAd94lII0KcBAGZC3T1Avu8FvdZCFfXqV3Kw81ZB2erRGZA7NmWStuIUYCMCdZCLZAzuVR4tNh7pTmZAPx9YVWZCnwkDieO6VTLQwnNXEAYVEkrgZBmrWQHzmZAMvblysCJTnihFXaQnUJHZAhoiPX0tkPOEj5FEtxBugbgKxOlRuJoAfpf8oJK2YI7',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => 'SM',
        //   'bot_name'				                => 'SMGiftCardsBot',
        //   'access_token'		                => 'EAACdkvNXe6cBANl7Bl6TUkzvPJZBVxlHbZAUqyooZCi3EmFQrxfqRMbZBFC0L8tkvE4gDpno4ZAp5T8G7zwDAnZBztjypUDLFXObB7j3Q17GHqXokhYlSSwNCtO51D5gAwTQBc9xiNSLypM0yqbABXsdLQT5YXU6evzoCj8MJPuZBPO1MMqPd6X',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => 'Purefoods',
        //   'bot_name'				                => 'PurefoodsHamRedemption',
        //   'access_token'		                => 'EAAEVe3ZASPwABAPzpjK1ZAoyOxEUk4nwu8QIcTywFFoArKJCiH7BxY4fuvLqNNAAFxwB2sl5JMZBKQlywQnpUzvgbtOOquUwEpoHwZAbLI5aTGcDUbAF97611xAfum5b2m2f7YlwoaXrfLJXn1SpZAGcoAFUG7P7qcQaLj4y5iBRgN7VjMLJX',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => 'National Bookstore',
        //   'bot_name'				                => 'NBSGiftCards',
        //   'access_token'		                => 'EAAETLgZCxk4YBABnwDbB59Y2W9OeoWbVpKa1SWhMidNVV9BJLoaxPsoeUAY5rUTvoqbHhCP5JRty2M439Ip4KO4XI6VmJpU0SBry1mjVoorHcI9V33LNCAHJVx1oawg9YbdsR0H0ceqIRTq9jHVtKr4mmxKkwZAeOEhVrwrDs3gxTHrgqQ',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => 'Swatch',
        //   'bot_name'				                => 'SwatchGiftCards',
        //   'access_token'		                => 'EAAQhIArK1LMBANThviZBn72r6EpZB5iu0ZCJfXzOgqZAiMHCk284uP4kysYZCaNAskXfQFm944VbT8F6D11UYwEwLdQi2ZCAY8l1kykZBRiKj3QsZBtmZB9VxHgKrIgLpn90Ch2zUe3yNzExrPx4C5jZBBExA3witXuEUekMUxGpmtk07UQvb8kkoL',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => 'Greenwich',
        //   'bot_name'				                => 'GreenwichGiftCards',
        //   'access_token'		                => 'EAAGtbZAGlYmIBACpTxeYgTtsPqhrvlaGiIO2CbqZAwMSlbLx2BsUYuLdz5RPof9v2z6durXEaRB1jH0ZADshs8KnRc9KHz2Yh7cZAkBCZCjIhWUNokT2e0TXcIniB1RjGp1uRlP7XSU0qK6egFU7mogPxRgckWl7zW8XQJbjDUy8xLIZBZAu997',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => 'Jollibee',
        //   'bot_name'				                => 'JolliGiftCards',
        //   'access_token'		                => 'EAAKljkejAZAUBAFVsz3d2NOfslZAn5RaoTACiZBenyt9Vqcs9mWEzCCw9nLv1pfwArEN5XEEeKCXkGs4i2NLISjYCCYJxeh658QJ2OTCzE5aetdhjfaZAozT6gMc7kaIHp97FV2ZCokbOeosrayd36lRx49frAxNXwPCjpDZCKu6Ogd3UFileC',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => "Robinson's",
        //   'bot_name'				                => 'RobinsonsGiftCards',
        //   'access_token'		                => 'EAAEKZAOnA5DQBAPQWnFiLoCeuc1bqq4B9TbIjbvdmR3Uyh67ShroBZBZBRyZAHmsCZCMdxMUZCjK1ZB9w0ctZAzsvFDbztP6r72UW6t4DoJk6V7izrlZC5yiJzp1lRVLVTSV0q8xhgZCNbfAl5xfNHVQg2DrR8ZC9MZAi0LzTdhYvZBCQIMPipQwVJkmm',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => "Sushi Nori",
        //   'bot_name'				                => 'SushiNoriRedemption',
        //   'access_token'		                => 'EAAGHf6lHvZCABAG9kCCNAi0or02MO7IkLQGHrUS9JAwqDPrZCjhAm3095SCSzIzAUsQ2KmyWe2pCg0MSZAmL08YY6EDhEQnxXQjfYnqz7nYEgm82DJzZAWDMmG34rsbzIQ7nDn02xJV0YtvZCKvEUcWN5b8giZAXJiL9fgZBbBxVr50XH9G7v74',
        // ],
        // [
        //   'convenience_fee'                 => '50.00',
        //   'name'			                      => "Burger King",
        //   'bot_name'				                => 'BurgerKingRedemption',
        //   'access_token'		                => 'EAAQBrmj6zVUBADJAdRInaHSqZAlFpLx49pzAjjLnyydBaRGZARS27MYMHsHXXhZCZCBihStUdN7GlcumAn24u4qkqYgQE9n3h3KFEaFXrtyzGARaszZBnCkZAuSbBcZA1PSsR6ZC5t4E7ovSWoZBu1p7ZB14hd02PHoYS20Uip6Vz0PZA7jnsPRihn2',
        // ]
      ];
      foreach ($data as $key)
      {
        Client::create([
          // 'convenience_fee'            => $key['convenience_fee'],
          'name'                       => $key['name'],
          // 'bot_name'                   => $key['bot_name'],
          // 'access_token'               => $key['access_token']
        ]);
      }
    }
}
