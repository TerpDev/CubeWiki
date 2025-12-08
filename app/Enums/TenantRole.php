<?php

namespace App\Enums;

enum TenantRole: string
{
    case OWNER = 'owner';
    case MEMBER = 'member';

    /**
     * Return a simple value => label map for form selects.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $role) => [$role->value => $role->label()])
            ->toArray();
    }

    public function label(): string
    {
        return match ($this) {
            self::OWNER => 'Owner',
            self::MEMBER => 'Member',
        };
    }
}
