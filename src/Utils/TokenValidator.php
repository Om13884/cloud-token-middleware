<?php
declare(strict_types=1);

namespace App\Utils;

use DateTime;

final class TokenValidator
{
    public static function isValid(string $token, ?DateTime $time = null): bool
    {
        if (strlen($token) !== 24) {
            return false;
        }

        if (!ctype_digit($token[0]) || !ctype_digit($token[1]) || !ctype_digit($token[4])) {
            return false;
        }

        $sum = ((int)$token[0]) + ((int)$token[1]);

        if ($sum > 9 || (int)$token[4] !== $sum) {
            return false;
        }

        $time ??= new DateTime();
        $hourHex = strtolower(dechex((int)$time->format('G')));

        return str_contains(strtolower($token), $hourHex);
    }
}
