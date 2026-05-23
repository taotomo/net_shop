<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'shop_id'     => Shop::factory(),
            'name'        => $this->faker->word(),
            'description' => $this->faker->text(100),
            'category'    => 'その他',
            'price'       => $this->faker->numberBetween(100, 10000),
            'stock'       => $this->faker->numberBetween(1, 50),
            'image'       => null,
        ];
    }
}
