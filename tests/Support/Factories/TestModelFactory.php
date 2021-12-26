<?php

namespace Iam2harsh\FaqsBuilder\Tests\Support\Factories;

use Iam2harsh\FaqsBuilder\Tests\Support\TestModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestModelFactory extends Factory
{
    protected $model = TestModel::class;

    public function definition(): array
    {
        return [
            'name' => 'test model',
        ];
    }
}
