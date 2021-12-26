<?php

namespace Iam2harsh\FaqsBuilder;

class Question
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $answer;

    /** @var bool */
    protected $active = false;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public static function make(string $title): self
    {
        return new static($title);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /** @param callable|string $answer */
    public function answer($answer): self
    {
        if (is_callable($answer)) {
            $this->answer = app()->call($answer);
            return $this;
        }

        $this->answer = $answer;

        return $this;
    }

    public function active(bool $active = true): self
    {
        $this->active = $active;

        return $this;
    }

    public function render(): array
    {
        return [
            'question' => $this->title,
            'answer' => $this->answer,
            'active' => $this->active,
        ];
    }
}
