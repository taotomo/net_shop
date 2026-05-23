<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            // ユーザーファクトリーは、ユーザーモデルのダミーデータを生成するためのクラス
            // nameはfakerのname()メソッドを使ってランダムな名前を生成
            // emailはfakerのunique()->safeEmail()メソッドを使ってランダムなメールアドレスを生成
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'password', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                // メール未確認のユーザーを作るため、email_verified_atをnullにする
                'email_verified_at' => null,
            ];
        });
    }
}
