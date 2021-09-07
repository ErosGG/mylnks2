<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Link::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => User::inRandomOrder()->first(),
            "title" => $this->faker->company(),
            "url" => $this->faker->url(),
            "status" => $this->faker->randomElement(["draft", "published", "restricted"]),
            "published_at" => $this->faker->dateTimeBetween("-5 years", "+1 year"),
        ];
    }
}
