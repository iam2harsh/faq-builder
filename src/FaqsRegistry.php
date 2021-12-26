<?php

namespace Iam2harsh\FaqsBuilder;

use Illuminate\Support\Arr;

class FaqsRegistry
{
    /** @var array<string> */
    private $faqs = [];

    public function make(string $name): Faqs
    {
        if (Arr::has($this->faqs, $name)) {
            return $this->faqs[$name];
        }

        $this->faqs[$name] = new Faqs($name);

        return $this->faqs[$name];
    }
}
