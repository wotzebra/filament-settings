<?php

namespace Wotz\FilamentSettings\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Wotz\FilamentSettings\Models\Setting;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition()
    {
        return [
            'key' => $this->faker->word,
            'value' => $this->faker->sentence,
        ];
    }
}
