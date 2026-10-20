<?php

declare(strict_types=1);

namespace JardisSupport\Contract\Tests\Unit\DbQuery;

use JardisSupport\Contract\DbQuery\DbComparisonOperatorsInterface;
use JardisSupport\Contract\DbQuery\LikePatternInterface;
use JardisSupport\Contract\Tests\Unit\ContractShape;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class LikePatternInterfaceTest extends TestCase
{
    public function testDeclaresExactlyWhatARendererNeeds(): void
    {
        ContractShape::assertMethods(LikePatternInterface::class, [
            'pattern' => '(): string',
            'escapeClause' => '(): string',
        ], true);
    }

    /** @return array<string, array{string}> */
    public static function likeMethods(): array
    {
        return ['like' => ['like'], 'notLike' => ['notLike']];
    }

    #[DataProvider('likeMethods')]
    public function testTheLikeMethodsNameThePatternTypeInTheirParamContract(string $method): void
    {
        $doc = (string) (new ReflectionMethod(DbComparisonOperatorsInterface::class, $method))->getDocComment();

        self::assertMatchesRegularExpression(
            '/@param\s+[^\n]*\|LikePatternInterface\s+\$value/',
            $doc,
            $method . '() must list LikePatternInterface in the @param type of $value'
        );
    }

    public function testAnImplementationCanBeUsedAsTheValueObjectOfTheContract(): void
    {
        $pattern = new class implements LikePatternInterface {
            public function pattern(): string
            {
                return '%50!%%';
            }

            public function escapeClause(): string
            {
                return " ESCAPE '!'";
            }
        };

        self::assertSame('%50!%%', $pattern->pattern());
        self::assertSame(" ESCAPE '!'", $pattern->escapeClause());
    }
}
