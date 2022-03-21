<?php

namespace App\Http\Controllers\Api;

// use App\Events\Customer\PasswordUpdated;

use Socialite;
use App\Customer;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Events\Customer\Registered;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\Validations\ApiSpcialLoginRequest;
use App\Http\Requests\Validations\RegisterCustomerRequest;
use App\Notifications\Auth\SendVerificationEmail as EmailVerificationNotification;
// use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Notifications\Auth\CustomerResetPasswordNotification as SendPasswordResetEmail;
use App\Notifications\Customer\PasswordUpdated as PasswordResetSuccess;

class AuthController extends Controller
{
    public function register(RegisterCustomerRequest $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'accepts_marketing' => $request->subscribe,
            'verification_token' => Str::random(40),
            'active' => 0,
        ]);

        // Sent email address verification notich to customer
        $customer->notify(new EmailVerificationNotification($customer));

        $customer->generateToken();

        event(new Registered($customer));

        return new CustomerResource($customer);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('customer')->attempt($credentials)) {

            $customer = Auth::guard('customer')->user();
            $customer->generateToken();

            return new CustomerResource($customer);
        }

        return response()->json(['message' => trans('api.auth_failed')], 401);
    }

    /**
     * Redirect the user to the facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialLogin(ApiSpcialLoginRequest $request, $provider)
    {
        try {
            $user = Socialite::driver($provider)->userFromToken($request->get('access_token'));
        }
        catch (\GuzzleHttp\Exception\ClientException $e) {
            return response()->json([
                'message'   => trans('api.auth_failed'),
                'errors'    => json_decode($e->getResponse()->getBody()->getContents(), true)
            ], 401);
        }

        $customer = Customer::where('email', $user->email)->first();

        if (! $customer) {
            $customer = new Customer;
            $customer->name = $user->getName();
            $customer->nice_name = $user->getNickname();
            $customer->email = $user->getEmail();
            $customer->active = 1;
            $customer->save();

            $customer->saveImageFromUrl($user->avatar_original ?? $user->getAvatar());
        }

        $customer->generateToken();

        return new CustomerResource($customer);
    }

    /**
     * Obtain the user information from facebook.
     *
     * @return \Illuminate\Http\Response
     */
    // public function handleSocialProviderCallback(Request $request, $provider)
    // {
    //     try {
    //         $user = Socialite::driver($provider)->stateless()->user();
    //         // $user = Socialite::driver($provider)->stateless()->user();
    //     } catch (\GuzzleHttp\Exception\ClientException $e) {
    //         // $response = json_decode($e->getResponse()->getBody()->getContents(), true);
    //         // return response()->json(['message' => trans('api.auth_failed') . ' ' . $response['error']['message']], 401);

    //         return response()->json([
    //             'message'   => trans('api.auth_failed'),
    //             'errors'    => json_decode($e->getResponse()->getBody()->getContents(), true)
    //         ], 401);
    //     }

    //     $customer = Customer::where('email', $user->email)->first();

    //     if ( ! $customer ) {
    //         $customer = new Customer;
    //         $customer->name = $user->getName();
    //         $customer->nice_name = $user->getNickname();
    //         $customer->email = $user->getEmail();
    //         $customer->active = 1;
    //         $customer->save();

    //         $customer->saveImageFromUrl($user->avatar_original ?? $user->getAvatar());
    //     }

    //     $customer->generateToken();

    //     return new CustomerResource($customer);
    // }

    public function logout(Request $request)
    {
        $customer = Auth::guard('api')->user();

        if ($customer) {
            $customer->api_token = null;
            $customer->save();
        }

        return response()->json(trans('api.auth_out'), 200);
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (! $customer) {
            return response()->json(['message' => trans('api.email_account_not_found')], 404);
        }

        $token = Str::random(60);
        $url = url('/api/auth/find/'.$token);

        $passwordReset = DB::table('password_resets')
                            ->updateOrInsert(
                                ['email' => $customer->email],
                                [
                                    'email' => $customer->email,
                                    'token' => $token,
                                    'created_at' => Carbon::now()
                                ]
                            );

        if ($customer && $passwordReset) {
            $customer->notify( new SendPasswordResetEmail($token, $url) );
        }

        return response()->json(['message' => trans('api.password_reset_link_sent')], 201);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = DB::table('password_resets')->where('token', $token)->first();
        if (! $passwordReset) {
            return response()->json(['message' => trans('api.password_reset_token_404')], 404);
        }

        if (Carbon::parse($passwordReset->created_at)->addMinutes(720)->isPast()) {
            DB::table('password_resets')->where('token', $token)->delete();

            return response()->json(['message' => trans('api.password_reset_token_invalid')], 404);
        }

        return response()->json($passwordReset);
    }

     /**
     * Reset password
     *
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     */
    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = DB::table('password_resets')->where('token', $request->token)->first();
        if (! $passwordReset) {
            return response()->json(['message' => trans('api.password_reset_token_404')], 404);
        }

        $customer = Customer::where('email', $passwordReset->email)->first();
        if (! $customer) {
            return response()->json(['message' => trans('api.email_account_not_found')], 404);
        }

        $customer->password = bcrypt($request->password);
        $customer->save();

        DB::table('password_resets')->where('token', $request->token)->delete();

        $customer->notify(new PasswordResetSuccess($customer));

        return response()->json(['message' => trans('api.password_reset_successful')], 200);
    }
}