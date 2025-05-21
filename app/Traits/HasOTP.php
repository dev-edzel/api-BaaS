<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HasOTP
{
    public function generateOTP($user): array
    {
        $email = $user->email;
        $otp = strtoupper(Str::random(6));
        $referenceNo = strtoupper(Str::random(10));
        $ts = now()->toDateTimeString();
        $hashed = hash_hmac(
            'sha256',
            "{$email}{$otp}{$ts}",
            config('jwt.secret')
        );

        return [
            'otp' => $otp,
            'reference_no' => $referenceNo,
            'hashed' => [
                'email' => $email,
                'otp' => $hashed,
                't' => $ts,
            ]
        ];
    }

    public function checkOTP($email, $otp, $hashedOTP, $hashDate): void
    {
        $ts = now()->subMinutes(5)->toDateTimeString();

        $isValid = $hashDate > $ts;
        $isMatched = hash_equals(
            hash_hmac(
                'sha256',
                "{$email}{$otp}{$hashDate}",
                config('jwt.secret')
            ),
            $hashedOTP
        );

        if (!$isValid || !$isMatched) {
            throw new HttpException(
                400,
                'Invalid verification.'
            );
        }
    }
}
