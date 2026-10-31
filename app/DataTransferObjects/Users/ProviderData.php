<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Users;

use App\Enums\LinkedAccountProvider;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProviderData extends Data
{
    public function __construct(
        public LinkedAccountProvider $name,
        public string $label,
        public string $color,
        public string $url,
        public bool $linked,
        public ?string $providerUserId = null,
    ) {}
}
