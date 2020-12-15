<?php

namespace Spatie\TypeScriptTransformer\Tests\Actions;

use PHPUnit\Framework\TestCase;
use Spatie\TypeScriptTransformer\Actions\ReplaceMissingSymbolsAction;
use Spatie\TypeScriptTransformer\Structures\TypesCollection;
use Spatie\TypeScriptTransformer\Tests\Fakes\FakeTransformedType;

class ReplaceMissingSymbolsActionTest extends TestCase
{
    private ReplaceMissingSymbolsAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = new ReplaceMissingSymbolsAction();
    }

    /** @test */
    public function it_can_replace_missing_symbols()
    {
        $collection = TypesCollection::create();

        $collection[] = FakeTransformedType::fake('Enum')->withNamespace('enums');
        $collection[] = FakeTransformedType::fake('Dto')
            ->withTransformed('{enum: {%enums\Enum%}, non-existing: {%non-existing%}}')
            ->withMissingSymbols([
                'enum' => 'enums\Enum',
                'non-existing' => 'non-existing',
            ]);

        $collection = $this->action->execute($collection);

        $this->assertEquals('{enum: enums.Enum, non-existing: any}', $collection['Dto']->transformed);
    }
}
