<?php

namespace Iam2harsh\FaqsBuilder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;

class Faqs
{
    use Conditionable;

    /** @var \Illuminate\Support\Collection */
    protected $questions = [];

    /** @var string */
    protected $title;

    /** @var Model */
    protected $model;

    /** @var array */
    private $context = [];

    /** @var bool */
    protected $hasBeenResolved = false;

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

    public function model(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function addQuestions(callable $callable): self
    {
        $this->questions[] = $callable;

        return $this;
    }

    /**
     * @param mixed ...$models
     */
    public function context(...$models): self
    {
        $keyedModels = [];

        
        foreach ($models as $model) {
            $keyedModels[Str::camel((new \ReflectionClass($model))->getShortName())] = $model;
        }

        $this->context = $keyedModels;

        if ($this->model) {
            $this->context = array_merge($this->context, [
                Str::camel((new \ReflectionClass($this->model))->getShortName()) => $this->model,
            ]);
        }

        return $this;
    }

    public function render(): array
    {
        if ($this->hasBeenResolved === false) {
            $this->resolveQuestions()
                ->renderQuestions();
        }

        return [
            'title' => $this->title,
            'faqs' => $this->questions->toArray(),
        ];
    }

    private function resolveQuestions(): self
    {
        $this->questions = collect($this->questions)
            ->transform(function ($question) {
                return app()->call($question, $this->getContext());
            })
            ->flatten();

        $this->hasBeenResolved = true;

        return $this;
    }

    private function renderQuestions(): self
    {
        $this->questions = $this->questions
            ->transform(function (Question $question) {
                return $question->render();
            });

        return $this;
    }

    private function getContext(): array
    {
        return collect($this->context)
            ->when($this->model, function($collection) {
                return $collection->push([
                    Str::camel((new \ReflectionClass($this->model))->getShortName()) => $this->model,
                ]);
            })
            ->flatten()
            ->mapWithKeys(function (Model $model): array {
                return [ 
                    Str::camel((new \ReflectionClass($model))->getShortName()) => $model,
                ];
            })
            ->all();
    }
}
