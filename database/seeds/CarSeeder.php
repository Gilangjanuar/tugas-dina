<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for ($i = 0 ; $i < 20 ; $i++){
            \App\Car::query()->create([
                'car_name' => $faker->randomElement(['Mobilio','Jazz','Brio','Civic','Agya','HRV','Yaris','Rush','Ertiga']),
                'car_type' => $faker->randomElement(['Manual','Automatic']),
                'car_color' => $faker->colorName,
                'car_capacity' => $faker->numberBetween(2,8),
                'car_price' => $faker->numberBetween(70000000,500000000),
                'created_at' => \Carbon\Carbon::now('Asia/Jakarta'),
            ]);
        }

        for ($i = 0 ; $i < 100 ; $i++){
            \App\Sale::query()->create([
                'car_id' => $faker->numberBetween(1,20),
                'sale_date' => $faker->date('Y-m-d','now'),
                'created_at' => \Carbon\Carbon::now('Asia/Jakarta'),
            ]);
        }
    }
}
