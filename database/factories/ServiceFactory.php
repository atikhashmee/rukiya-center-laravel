<?php

namespace Database\Factories;

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
            'id_code' => $this->faker->unique()->slug(3),
            'category' => 'general',
            'title' => $this->faker->words(3, true),
            'tagline' => $this->faker->sentence(5),
            'description' => $this->faker->paragraph(3),
            'icon' => $this->faker->randomElement(['zap', 'heart', 'feather', 'shield']),
            'card_color' => 'border-indigo-600',
            'features' => [$this->faker->sentence(4), $this->faker->sentence(4)],
            'order' => 1,
            'price_type' => 'FIXED',
            'price_value' => 0.00,
            'min_donation' => null,
            'requires_custom_assessment' => false,
            'required_form_fields' => null,
            'submit_button_text' => 'Book Now',
        ];
    }

    // --- Service Category States ---

    /**
     * State for Counseling services.
     */
    public function counseling(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'category' => 'counseling',
                'icon' => 'handshake',
                'card_color' => 'border-indigo-600',
                'required_form_fields' => [],
                'price_value' => 0.00,
            ];
        })->sequence(
            // Counseling: The Discovery Path (FREE)
            [
                'id_code' => 'COUNSELING_FREE',
                'title' => 'The Discovery Path',
                'tagline' => 'First 30 Minutes: Complimentary',
                'description' => 'This introductory session helps you determine if the DK Healing Center is the right fit for your deeper needs—no commitment required.',
                'price_type' => 'FREE',
                'submit_button_text' => 'Confirm Free Booking',
                'features' => ['30-minute one-on-one session.', 'Initial assessment and spiritual guidance.', 'Zero cost.'],
                'order' => 1,
            ],
            // Counseling: The Transformation Path (FIXED - Paid session placeholder)
            [
                'id_code' => 'COUNSELING_PAID_STANDARD',
                'title' => 'The Transformation Path',
                'tagline' => 'Available after initial consultation',
                'description' => 'For those ready for deep, sustained change, this offers extended, scheduled guidance and customized spiritual program design.',
                'price_type' => 'FIXED', // Example fixed price for a standard session
                'price_value' => 75.00,
                'submit_button_text' => 'Proceed to Booking',
                'features' => ['Extended session duration (60+ min).', 'Customized spiritual program design.', 'Continuous support.'],
                'order' => 2,
            ]
        );
    }

    /**
     * State for Rukiya services.
     */
    public function rukiya(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'category' => 'rukiya',
                'icon' => 'shield',
                'card_color' => 'border-red-600',
                'required_form_fields' => [],
            ];
        })->sequence(
            // Rukiya: The Guided Path (FIXED)
            [
                'id_code' => 'RUKIYA_GUIDED',
                'title' => 'The Guided Path (Normal Healing)',
                'tagline' => 'Standard Healing Session - £250.00',
                'description' => 'Covers straightforward concerns using recitation and simple activities guided by the practitioner. Focuses on cleansing and initial protection.',
                'price_type' => 'FIXED',
                'price_value' => 250.00,
                'submit_button_text' => 'Proceed to £250.00 Payment',
                'features' => ['Done by simple patient activity.', 'Recitation for clarity and protection.', 'One-time session.'],
                'order' => 1,
            ],
            // Rukiya: The Intensive Path (RESERVATION)
            [
                'id_code' => 'RUKIYA_DEEP',
                'title' => 'The Intensive Path (Deep Healing)',
                'tagline' => 'Assessment Required - Price Confirmed Later',
                'description' => 'Advanced healing for complex spiritual challenges. Requires a personalized assessment by the spiritual guru before treatment and payment.',
                'price_type' => 'RESERVATION',
                'requires_custom_assessment' => true,
                'submit_button_text' => 'Request Assessment & Reservation',
                'features' => ['Advanced, personalized methods.', 'Detailed spiritual assessment.', 'Price determined by the spiritual guru.'],
                'order' => 2,
            ]
        );
    }

    /**
     * State for Istekhara services.
     */
    public function istekara(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'category' => 'istekhara',
                'icon' => 'eye',
                'card_color' => 'border-theme-gold',
                'required_form_fields' => ['motherName'], // Istekhara specific requirement
            ];
        })->sequence(
            // Istekhara: The Guidance Path (DONATION)
            [
                'id_code' => 'ISTEKHARA_GUIDANCE',
                'title' => 'The Guidance Path (Normal)',
                'tagline' => 'Donation Request (Min £11)',
                'description' => 'Provides quick, meaningful spiritual insight into common dilemmas. We request a minimum donation to support our services.',
                'price_type' => 'DONATION',
                'min_donation' => 11.00,
                'submit_button_text' => 'Proceed to Donation Payment',
                'features' => ['Suitable for common, clear questions.', 'Minimum donation requested.', 'Requires full name and mother\'s name.'],
                'order' => 1,
            ],
            // Istekhara: The Definitive Path (FIXED)
            [
                'id_code' => 'ISTEKHARA_DEEP',
                'title' => 'The Definitive Path (Deep)',
                'tagline' => 'Fixed Price: £50.00',
                'description' => 'Intensive, personalized session designed for complex, high-stakes decisions. Includes a deep reading and exhaustive guidance.',
                'price_type' => 'FIXED',
                'price_value' => 50.00,
                'submit_button_text' => 'Proceed to £50.00 Payment',
                'features' => ['Intensive reading for complex matters.', 'Detailed spiritual report.', 'Guaranteed priority booking.'],
                'order' => 2,
            ]
        );
    }
}
