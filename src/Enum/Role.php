<?php declare(strict_types=1);

namespace App\Enum;

class Role
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @return string[]
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
        ];
    }
}
