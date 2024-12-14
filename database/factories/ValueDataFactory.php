<?php

namespace Database\Factories;

use App\Models\ValueData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ValueData>
 */
class ValueDataFactory extends Factory
{
  protected $model = ValueData::class;

  public function definition(): array
  {
    return [
      'key' => $this->faker->word,
      'value' => ['example' => $this->faker->sentence],
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
