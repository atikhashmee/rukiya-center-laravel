<?php

namespace Database\Factories;

use App\ServiceType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service_name' => $this->faker->catchPhrase(),
            'service_type' => $this->faker->randomElement(array_column(ServiceType::cases(), 'value')),
            'price' => $this->faker->randomNumber(5, false),
            'start_date_and_time' => $this->faker->date().''.$this->faker->time(),
            'end_date_and_time' => $this->faker->date().''.$this->faker->time(),
            'description' => implode(',', $this->faker->sentences(5)),
            'duration' => $this->faker->randomElement(['45', 30]),
        ];
    }
}
