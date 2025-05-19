<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\Plain;

class JWTMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();
        $appUrl = config('app.url');

        if (!$token) {
            return response()->failed('Unauthorized: Missing token');
        }

        try {
            $parser = new Parser(new JoseEncoder());
            $parsedToken = $parser->parse($token);

            $this->validateToken($parsedToken, $appUrl);

            $uid = $parsedToken->claims()->get('sub');
            if (!$uid) {
                throw new Exception('Invalid token payload');
            }

            $user = User::find($uid);
            if (!$user) {
                throw new Exception('User not found');
            }

            Auth::setUser($user);
            $request->merge(['uid' => $uid]);

        } catch (Exception $e) {
            return response()->failed('Invalid token: ' . $e->getMessage());
        }

        return $next($request);
    }

    /**
     * @throws Exception
     */
    private function validateToken(Plain $token, string $url): void
    {
        $uniqueID = $token->claims()->get('jti');
        if (Cache::has("blacklisted_tokens:{$uniqueID}")) {
            throw new Exception('Token has been invalidated');
        }

        if (!$token->isPermittedFor($url)) {
            throw new Exception('Token not permitted for this application');
        }

        if (!$token->hasBeenIssuedBy($url)) {
            throw new Exception('Token not issued by this application');
        }

        if ($token->isExpired(new DateTimeImmutable())) {
            throw new Exception('Token has expired');
        }
    }
}
