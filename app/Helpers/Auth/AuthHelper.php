<?php

namespace App\Helpers\Auth;

use App\Helpers\Venturo;
use App\Http\Resources\UserResource;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * Helper khusus untuk authentifikasi pengguna
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class AuthHelper extends Venturo
{
    /**
     * Proses validasi email dan password
     * jika terdaftar pada database dilanjutkan generate token JWT
     *
     * @param  string  $email
     * @param  string  $password
     * @return void
     */
    public static function login($email, $password)
    {
        try {
            $credentials = ['email' => $email, 'password' => $password];
            if (! $token = JWTAuth::attempt($credentials)) {
                return [
                    'status' => false,
                    'error' => ['Invalid email or password.'],
                ];
            }
        } catch (JWTException $e) {
            return [
                'status' => false,
                'error' => ['Could not create token.'],
            ];
        }
        return [
            'status' => true,
            'data' => self::createNewToken($token),
        ];
    }
    /**
     * Mengarahkan pengguna ke provider OAuth
     *
     * @param string $provider
     * @return mixed
     */
    public static function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }
    public static function handleGoogleCallback()
    {
        try {
            $oauthUser = Socialite::driver('google')->stateless()->user();
            $user = UserModel::where('email', $oauthUser->getEmail())->first();
            if (!$user) {
                $user = UserModel::create([
                    'name' => $oauthUser->getName(),
                    'email' => $oauthUser->getEmail(),
                    'password' => bcrypt(Str::random(24)),
                    'photo' => $oauthUser->getAvatar(),
                ]);
            }
            $token = JWTAuth::fromUser($user);
            return [
                'status' => true,
                'data' => [
                    'access_token' => $token,
                    'user' => new UserResource($user)
                ],
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected static function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource(auth()->user()),
            // 'user' => new UserResource(\Illuminate\Support\Facades\Auth::user()),
        ];
    }
    public static function logout()
    {
        try {
            $removeToken = JWTAuth::invalidate(JWTAuth::getToken());
            if ($removeToken) {
                return [
                    'status' => true,
                    'message' => 'Logout Success!',
                ];
            }
        } catch (JWTException $e) {
            return [
                'status' => false,
                'error' => ['Could not logout token.'],
            ];
        }
    }
}
