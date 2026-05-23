<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id'     => User::factory(),
            'name'        => $this->faker->company(),
            'description' => $this->faker->text(100),
            'image'       => null,
        ];
    }
}
