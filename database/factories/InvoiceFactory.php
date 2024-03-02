<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->numberBetween(10, 1000),
            'customer_id' => $this->faker->numberBetween(1, 20),
            'date' => $this->faker->date,
            'sue_date' => $this->faker->date,
            'reference' => 'REF-'.rand(10, 5000),
            'terms_and_condition' => $this->faker->paragraph(2),
            'sub_total' => $this->faker->numberBetween(10, 10000),
            'discount' => $this->faker->numberBetween(10, 100),
            'total' => $this->faker->numberBetween(200, 2000),
        ];
    }
}
