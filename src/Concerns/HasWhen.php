<?php

namespace Iam2harsh\FaqsBuilder\Concerns;

trait HasWhen
{
    /**
     * @param mixed $value
     * @return $this 
     */
    public function when($value, callable $callback, ?callable $default = null): self
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        }

        if ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }
}