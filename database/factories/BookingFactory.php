<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceTypes = [
            'ISTEKHARA_DEFINITIVE',
            'RUKIYA_GUIDED',
            'COUNSELING_PAID',
        ];

        $priceTypes = ['FIXED', 'DONATION'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        $bookingStatuses = ['new', 'confirmed', 'in_progress', 'completed'];

        return [
            // Link to a customer or keep null for guest bookings
            'customer_id' => null,
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'mother_name' => fake()->firstNameFemale(),
            'inquiry_description' => fake()->paragraph(3),
            'service_id' => fake()->randomElement($serviceTypes),
            'price_type' => fake()->randomElement($priceTypes),
            'service_price' => fake()->randomFloat(2, 11, 300),
            'payment_status' => fake()->randomElement($paymentStatuses),
            'booking_status' => fake()->randomElement($bookingStatuses), // Populated booking status
            'phone_number' => fake()->phoneNumber(),
        ];
    }
}
