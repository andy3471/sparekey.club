<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Shared;

use App\DataTransferObjects\Users\UserData;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AuthData extends Data
{
    public function __construct(
        public ?UserData $user = null,
    ) {}
}
