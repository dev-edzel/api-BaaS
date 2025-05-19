<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSymmetricToken extends Command
{
    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Secret
    |--------------------------------------------------------------------------
    |
    | Don't forget to set this in your .env file, as it will be used to sign
    | your tokens. A helper command is provided for this:
    | `php artisan jwt:secret`
    |
    | Note: This will be used for Symmetric algorithms only (HMAC),
    | since RSA and ECDSA use a private/public key combo.
    |
    */

    protected $signature = 'jwt:secret';
    protected $description = 'Command to generate a JWT secret and save it to the .env file.';

    public function handle(): void
    {
        $jwtSecret = base64_encode(random_bytes(64));

        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        $keyValue = "JWT_SECRET=$jwtSecret";

        if (str_contains($envContent, 'JWT_SECRET=')) {
            $envContent = preg_replace('/JWT_SECRET=.*/', $keyValue, $envContent);
        } else {
            $envContent .= PHP_EOL . $keyValue;
        }

        File::put($envPath, $envContent);

        $this->info('JWT Secret generated successfully');
        $this->info($keyValue);
    }
}
