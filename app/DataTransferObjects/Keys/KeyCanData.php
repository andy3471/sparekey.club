<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Keys;

use App\Enums\ClaimDeniedReason;
use App\Models\Key;
use App\Services\ClaimEligibility;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class KeyCanData extends Data
{
    public function __construct(
        public bool $view,
        public bool $claim,
        public bool $delete,
        public bool $feedback,
        public ?ClaimDeniedReason $claimDeniedReason = null,
        public ?string $cooldownEndsAt = null,
        public ?string $ownedKeyId = null,
    ) {}

    public static function fromModel(Key $key): self
    {
        /** @var array{view: bool, claim: bool, delete: bool, feedback: bool, claimDeniedReason: ?ClaimDeniedReason, cooldownEndsAt: ?string, ownedKeyId: ?string} $eligibility */
        $eligibility = app(ClaimEligibility::class)->for($key, auth()->user());

        return new self(...$eligibility);
    }
}
