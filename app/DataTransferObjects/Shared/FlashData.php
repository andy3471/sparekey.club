<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Shared;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FlashData extends Data
{
    public function __construct(
        public ?string $message = null,
        public ?string $error = null,
        public ?string $status = null,
    ) {}
}
