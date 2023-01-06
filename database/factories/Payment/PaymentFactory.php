<?php

namespace Database\Factories\Payment;

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

        $names = ["jiri.stromsik", "michal.dobes", "ales.medek", "pavel.kramolis", "sarka.nedelova", "lenka.sevcikova", "vanda.adamcikova"];

        return [
            "payer" => $names[array_rand($names)],
            "author" => $names[array_rand($names)],
            "type" => "normal",
            "title" => Lorem::text("40"),
            "amount" => rand(10, 555),
            "due" => $date->format('Y-m-d H:i:s')
        ];
    }
}
