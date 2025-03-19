<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{

    // Generate unique E.164 AU/NZ phone numbers as required
    private function generateE164PhoneNumber(): string
    {
        $isAU = (bool)random_int(0, 1);
        return $this->faker->unique()->numerify(
            $isAU
                ? '+614########'
                : '+642########'
        );
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstName' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->generateE164PhoneNumber(),
        ];
    }
}
