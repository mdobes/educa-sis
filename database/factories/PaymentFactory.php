<?php

namespace Database\Factories;

use Faker\Provider\Lorem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $date = Carbon::create(rand(2023, 2024), rand(1, 12), rand(1, 28), rand(0, 24), rand(1, 60), 0);

        return [
            "payer" => "michalldbs@seznam.cz",
            "author" => "michal@dbes.cz",
            "type" => "normal",
            "title" => Lorem::text("40"),
            "amount" => rand(10, 6009),
            "due" => $date->format('Y-m-d H:i:s')
        ];
    }
}
