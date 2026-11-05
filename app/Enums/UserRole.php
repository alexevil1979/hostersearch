<?php

namespace App\Enums;

enum UserRole: string
{
    case User = 'user';
    case Partner = 'partner';
    case Admin = 'admin';
    case SuperAdmin = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::User => 'User',
            self::Partner => 'Partner',
            self::Admin => 'Admin',
            self::SuperAdmin => 'Super Admin',
        };
    }

    public function isStaff(): bool
    {
        return in_array($this, [self::Admin, self::SuperAdmin], true);
    }

    public function canAccessAdmin(): bool
    {
        return $this->isStaff();
    }

    public function canAccessPartner(): bool
    {
        return in_array($this, [self::Partner, self::Admin, self::SuperAdmin], true);
    }
}
