<?php

namespace Iam2harsh\FaqsBuilder\Tests\Support;

use Iam2harsh\FaqsBuilder\Tests\Support\Factories\TestModelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return TestModelFactory::new();
    }
}
