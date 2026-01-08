<?php
declare(strict_types=1);

namespace Tests;

use App\Utils\TokenValidator;
use DateTime;
use PHPUnit\Framework\TestCase;

final class TokenValidatorTest extends TestCase
{
public function testValidToken(): void
{
    $time = new DateTime('2024-01-01 14:00:00'); // hour = 14 -> hex 'e'

    $token =
        '1' .        // index 0
        '2' .        // index 1
        'x' .        // index 2
        'y' .        // index 3
        '3' .        // index 4 (1 + 2)
        'e' .        // index 5 (hex hour)
        '123456789012345678'; // fill to length 24

    $this->assertSame(24, strlen($token));
    $this->assertTrue(TokenValidator::isValid($token, $time));
}



    public function testInvalidTokenLength(): void
    {
        $time = new DateTime('2024-01-01 10:00:00');
        $this->assertFalse(TokenValidator::isValid('short-token', $time));
    }

    public function testInvalidSumRule(): void
    {
        $time = new DateTime('2024-01-01 08:00:00');
        $hourHex = dechex(8);

        $token = "13{$hourHex}345678901234567890";

        $this->assertFalse(TokenValidator::isValid($token, $time));
    }

    public function testMissingHexHour(): void
    {
        $time = new DateTime('2024-01-01 22:00:00');

        $token = "12034567890123456789012";

        $this->assertFalse(TokenValidator::isValid($token, $time));
    }
}
