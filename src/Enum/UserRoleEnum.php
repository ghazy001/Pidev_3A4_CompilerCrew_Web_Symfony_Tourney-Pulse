<?php

namespace App\Enum;

class UserRoleEnum
{
    public const ADMIN = 'admin';
    public const Client = 'Client';

    public static function getAllRoles(): array
    {
        return [
            self::ADMIN,
            self::Client,
        ];
    }
}
