<?php

namespace Database\Seeders;


use App\Models\MarketProduct;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Notification::factory()->count(300)->create();
    }

}
