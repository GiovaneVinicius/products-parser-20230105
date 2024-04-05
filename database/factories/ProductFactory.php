<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->randomNumber(),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'imported_t' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'url' => $this->faker->url,
            'creator' => $this->faker->name,
            'created_t' => $this->faker->randomNumber(),
            'last_modified_t' => $this->faker->randomNumber(),
            'product_name' => $this->faker->word,
            'quantity' => $this->faker->randomNumber(2),
            'brands' => $this->faker->word,
            'categories' => $this->faker->word,
            'labels' => $this->faker->word,
            'cities' => $this->faker->city,
            'purchase_places' => $this->faker->word,
            'stores' => $this->faker->word,
            'ingredients_text' => $this->faker->sentence,
            'traces' => $this->faker->word,
            'serving_size' => $this->faker->randomNumber(2),
            'serving_quantity' => $this->faker->randomNumber(2),
            'nutriscore_score' => $this->faker->randomNumber(2),
            'nutriscore_grade' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'main_category' => $this->faker->word,
            'image_url' => $this->faker->imageUrl(),
        ];
    }
}
