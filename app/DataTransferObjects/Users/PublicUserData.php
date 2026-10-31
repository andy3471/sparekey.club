<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Users;

use App\Models\User;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class PublicUserData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public ?string $avatar = null,
        public ?string $bio = null,
        public ?int $karma = null,
        public ?string $karma_colour = null,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: (string) $user->id,
            name: $user->name,
            avatar: $user->avatar,
            bio: $user->bio,
            karma: $user->karma !== null ? (int) $user->karma : null,
            karma_colour: $user->karma_colour ?? 'bg-primary-600 text-white shadow-primary-600/30',
        );
    }
}
