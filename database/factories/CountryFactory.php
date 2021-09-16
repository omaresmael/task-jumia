<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'country' => $this->faker->country,
            'state' => $this->faker->boolean,
            'country_code' => '+'.$this->faker->numberBetween(111,999),
            'phone_number' => $this->faker->numberBetween(111111111,9999999999),
        ];
    }
}
