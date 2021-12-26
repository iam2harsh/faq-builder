<?php

namespace Iam2harsh\FaqsBuilder\Facades;

use Iam2harsh\FaqsBuilder\FaqsRegistry;
use Illuminate\Support\Facades\Facade;


class Faqs extends Facade
{
    /**
     * @method \Iam2harsh\FaqsBuilder\Faqs make(string $name)
     * @method \Iam2harsh\FaqsBuilder\Faqs addQuestions($questions)
     */
    protected static function getFacadeAccessor(): string
    {
        return FaqsRegistry::class;
    }
}