<?php

namespace Wotz\FilamentSettings\Database\Factories;

use Wotz\FilamentSettings\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

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
