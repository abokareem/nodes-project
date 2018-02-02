<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TwoFaLoginRequest;
use App\Http\Resources\TwoFaLoginFirstStep;
use App\Services\TwoFaService;
use App\User;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use League\OAuth2\Server\Exception\OAuthServerException;
use PragmaRX\Google2FA\Google2FA;
use Psr\Http\Message\ServerRequestInterface;

class UserAuthController extends Controller
{
    public function login(
        ServerRequestInterface $request,
        AccessTokenController $accessToken,
        TwoFaService $twoFaService,
        Google2FA $google2FA
    )
    {
        return $google2FA->generateSecretKey();
        $tokens = $accessToken->issueToken($request);

        if ($tokens->status() === 200) {

            $user = User::where('email', $request->getParsedBody()['username'])->firstOrFail();

            if ($twoFaService->isEnable($user)) {

                $user->twoFa()->delete();

                $userLoginRecord = $user->twoFa()->create([
                    'token' => encrypt($user->email),
                    'auth_user_data' => $tokens
                ]);

                return new TwoFaLoginFirstStep($userLoginRecord);
            }
        }

        return $tokens;
    }

    public function twoFaLogin(TwoFaLoginRequest $request, Google2FA $google2FA)
    {
        $user = User::where('email', decrypt($request->get('token')))->firstOrFail();

        $code = $request->input('code');

        $valid = $google2FA->verifyKey("YP5S7NFNYKMJQVSU",$code,10000);

        dd($valid);

        if ($valid) {

            return $user->twoFa()->firstOrFail()->auth_user_data;

        }

        throw OAuthServerException::invalidCredentials();
    }
}
