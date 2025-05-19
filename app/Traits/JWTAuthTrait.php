<?php

namespace App\Traits;

use App\Helpers\JWTHelper;
use DateTimeImmutable;
use Exception;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;
use Throwable;

trait JWTAuthTrait
{
    protected function getJwtConfig(): Configuration
    {
        return JWTHelper::getJwtConfiguration();
    }

    protected function generateToken(string $userID): Plain
    {
        $config = $this->getJwtConfig();
        $now = new DateTimeImmutable();

        return $config->builder()
            ->issuedBy(config('app.url'))
            ->permittedFor(config('app.url'))
            ->identifiedBy(bin2hex(random_bytes(16)))
            ->relatedTo($userID)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+' . config('jwt.ttl') . ' minutes'))
            ->getToken($config->signer(), $config->signingKey());
    }

    public function createToken(): Plain
    {
        return $this->generateToken((string)$this->id);
    }

    public function refreshToken(string $oldTokenString): Plain
    {
        if (empty($oldTokenString)) {
            throw new Exception('Token string is empty');
        }

        try {
            $config = $this->getJwtConfig();
            $token = $config->parser()->parse($oldTokenString);
            $claims = $token->claims();

            $userID = $claims->get('sub');
            $uniqueID = $claims->get('jti');

            if (!$userID || !$uniqueID || (string)auth()->id() !== (string)$userID) {
                throw new Exception('Invalid token');
            }

            $this->blacklistToken($uniqueID);
            return $this->generateToken($userID);
        } catch (Throwable $e) {
            throw new Exception('Invalid token: ' . $e->getMessage());
        }
    }

    public function destroyToken(string $tokenString): void
    {
        if (empty($tokenString)) {
            throw new Exception('Token string is empty');
        }

        $config = $this->getJwtConfig();
        $token = $config->parser()->parse($tokenString);
        $claims = $token->claims();

        $userID = $claims->get('sub');
        $uniqueID = $claims->get('jti');

        if (!$userID || !$uniqueID || (string)auth()->id() !== (string)$userID) {
            throw new Exception('Invalid token');
        }

        $this->blacklistToken($uniqueID);
    }

    protected function blacklistToken(string $uniqueID): void
    {
        $blacklistKey = "blacklisted_tokens:{$uniqueID}";
        cache()->put($blacklistKey, true, now()->addMinutes(config('jwt.ttl')));

        if (!cache()->has($blacklistKey)) {
            throw new Exception('Failed to invalidate token');
        }
    }
}
