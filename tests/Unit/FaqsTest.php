<?php

namespace Iam2harsh\FaqsBuilder\Tests\Unit;

use Iam2harsh\FaqsBuilder\Facades\Faqs;
use Iam2harsh\FaqsBuilder\Question;
use Iam2harsh\FaqsBuilder\Tests\TestCase;
use Iam2harsh\FaqsBuilder\Tests\Support\TestModel;
use Spatie\Snapshots\MatchesSnapshots;

class FaqsTest extends TestCase
{
    use MatchesSnapshots;

    /** @test **/
    public function can_generate_faqs(): void
    {
        $faqs = Faqs::make('test faqs')
            ->addQuestions(function (): array {
                return [
                    Question::make('What is the meaning of life?')
                        ->answer('The answer to that question is 42'),
                ];
            })
            ->render();

        $this->assertMatchesJsonSnapshot($faqs);
    }

    /** @test **/
    public function can_specify_a_model(): void
    {
        $model = TestModel::factory()->make();

        $faqs = Faqs::make('test')
            ->model($model)
            ->addQuestions(function (TestModel $testModel): array {
                return [
                    Question::make('What is the name of this model?')
                        ->answer("The name of this model is {$testModel->name}"),
                ];
            })
            ->render();

        $this->assertMatchesJsonSnapshot($faqs);
    }

    /** @test **/
    public function can_add_context(): void
    {
        $model = TestModel::factory()->make();

        $faqs = Faqs::make('test')
            ->context($model)
            ->addQuestions(function (TestModel $testModel): array {
                return [
                    Question::make('What is the name of this model?')
                        ->answer("The name of this model is {$testModel->name}"),
                ];
            })
            ->render();

        $this->assertMatchesJsonSnapshot($faqs);
    }

    /** @test **/
    public function can_resolve_question(): void
    {
        $model = TestModel::factory()->make();

        $faqs = Faqs::make('test')
            ->model($model)
            ->addQuestions(function (TestModel $testModel): array {
                return [
                    Question::make('What is the name of this test model?')
                        ->answer(function () use ($testModel) {
                            return $testModel->name;
                        }),
                ];
            })
            ->render();

        $this->assertMatchesJsonSnapshot($faqs);
    }

    /** @test **/
    public function can_mark_question_as_active(): void
    {
        $faqs = Faqs::make('test')
            ->addQuestions(function (): array {
                return [
                    Question::make('Is this test question active?')
                        ->answer('yes this test is active')
                        ->active(),
                ];
            })
            ->render();

        $this->assertTrue($faqs['faqs'][0]['active']);
    }

    /** @test **/
    public function question_is_shown_when_true(): void
    {
        $faqs = Faqs::make('test')
            ->when(true, static function (\Iam2harsh\FaqsBuilder\Faqs $faqs): void {
                $faqs->addQuestions(function () {
                    return Question::make('Will this test question be hidden?')
                        ->answer('No this test question should not be hidden.');
                });
            })
            ->render();

        $this->assertMatchesJsonSnapshot($faqs);
    }

    /** @test **/
    public function question_is_not_shown_when_false(): void
    {
        $faqs = Faqs::make('test')
            ->when(false, static function (\Iam2harsh\FaqsBuilder\Faqs $faqs): void {
                $faqs->addQuestions(function () {
                    return Question::make('Can this test question be hidden?')
                        ->answer('Yes some test questions can be hidden.');
                });
            })
            ->render();

        $this->assertEmpty($faqs['faqs']);
    }

    /** @test **/
    public function question_is_shown_unless_false(): void
    {
        $faqs = Faqs::make('test')
            ->unless(false, static function (\Iam2harsh\FaqsBuilder\Faqs $faqs): void {
                $faqs->addQuestions(function () {
                    return Question::make('Will this test question be hidden?')
                        ->answer('No this test question should not be hidden.');
                });
            })
            ->render();

        $this->assertMatchesJsonSnapshot($faqs);
    }

    /** @test **/
    public function question_is_not_shown_unless_true(): void
    {
        $faqs = Faqs::make('test')
            ->unless(true, static function (\Iam2harsh\FaqsBuilder\Faqs $faqs): void {
                $faqs->addQuestions(function () {
                    return Question::make('Can this test question be hidden?')
                        ->answer('Yes some test questions can be hidden.');
                });
            })
            ->render();

        $this->assertEmpty($faqs['faqs']);
    }
}
