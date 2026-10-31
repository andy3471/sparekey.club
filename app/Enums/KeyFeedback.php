<?php

declare(strict_types=1);

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum KeyFeedback: string
{
    case Worked      = 'worked';
    case DidNotWork  = 'did_not_work';

    public static function sharerKarmaDeltaBetween(?self $from, ?self $to): int
    {
        $fromDelta = $from?->sharerKarmaDelta() ?? 0;
        $toDelta   = $to?->sharerKarmaDelta()   ?? 0;

        return $toDelta - $fromDelta;
    }

    public function isNegative(): bool
    {
        return $this === self::DidNotWork;
    }

    public function sharerKarmaDelta(): int
    {
        return match ($this) {
            self::Worked     => 1,
            self::DidNotWork => -1,
        };
    }
}
