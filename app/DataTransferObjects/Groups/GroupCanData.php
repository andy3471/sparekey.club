<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Groups;

use App\Enums\GroupRole;
use App\Models\Group;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class GroupCanData extends Data
{
    public function __construct(
        public bool $update,
        public bool $delete,
        public bool $leave,
        public bool $manageMembers,
        public bool $invite,
    ) {}

    public static function fromModel(Group $group): self
    {
        $user = auth()->user();

        if (! $user) {
            return new self(
                update: false,
                delete: false,
                leave: false,
                manageMembers: false,
                invite: false,
            );
        }

        $role       = $group->memberRole($user);
        $isOwner    = $group->owner_id === $user->id;
        $isAdminish = $role            === GroupRole::Owner || $role === GroupRole::Admin;

        return new self(
            update: $isAdminish,
            delete: $isOwner,
            leave: $role !== null && ! $isOwner,
            manageMembers: $isAdminish,
            invite: $isAdminish,
        );
    }
}
