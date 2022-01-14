<?php

declare(strict_types=1);

namespace App\Enum;

enum UserSex: int
{
    case Male = 1;
    case Female = 2;
    case Other = 3;
    public function getSexFormatted(): string
    {
        return match ($this) {
            self::Male => 'Homme',
            self::Female => 'Femme',
            self::Other => 'Autre',
        };
    }
}
