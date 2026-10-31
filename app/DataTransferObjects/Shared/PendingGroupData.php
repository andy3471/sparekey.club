<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Shared;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PendingGroupData extends Data
{
    public function __construct(
        public string $name,
        public ?string $avatar = null,
    ) {}
}
