<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;
use Jenssegers\Agent\Agent;

class AuthController extends Controller
{
    public function checkEmail(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users',
            'password' => 'nullable'
        ]);
        $user = User::where('email',$credentials['email'])->first();
        $user->setHidden([])->setVisible(['password','id']);
        if ( $user->password ) {
            $check = true;
        } else {
            $check = false;
        }
        return ['status' => $check,"data" => $user];
    }
    public function register(Request $request,$userId) {
        $Agent = new Agent();
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                /*Password::min(8)->mixedCase()->numbers()*/
            ],
            'remember' => 'boolean'
        ]);
        $user = User::findOrFail($userId);
        $credentials['password'] = bcrypt($credentials['password']);

        $user->update([
            "password" => $credentials['password']
        ]);

        $device = 'Desktop';
        if ( $Agent->isMobile() ) {
            $device = 'Mobile';
        }
        $token = $user->createToken($device)->plainTextToken;
        $userData = $user->load('role');
        $userData['avatar'] = URL::to($userData['avatar']);
        return [
            'data' => $userData,
            'permissions' => $user->role->permissions->pluck('key'),
            'token' => $token,
        ];
    }
    public function login(Request $request) {
        $Agent = new Agent();
        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required',
            'remember' => 'boolean'
        ]);
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);
        if ( !Auth::attempt($credentials,$remember) ) {
            return response([
                "error" => "Unauthorized"
            ],422);
        }
        $user = Auth::user();
        $device = 'Desktop';

        $deviceCount = json_decode($user->settings)->devices[0]->count;
        if ( $Agent->isMobile() ) {
            $device = 'Mobile';
            $deviceCount = json_decode($user->settings)->devices[1]->count;
        }


        $numberOfExistingTokensByDevice = $user->tokens()->where([['tokenable_id',$user->id],['name',$device]])->count();
        $numberOfAllowedTokensByDevice = $deviceCount;
        if ( $numberOfExistingTokensByDevice >= $numberOfAllowedTokensByDevice ) {
            return response([
                "error" => "Too Many Tokens"
            ],422);
        }

        $token = $user->createToken($device)->plainTextToken;
        $userData = $user->load('role');
        $userData['avatar'] = URL::to($userData['avatar']);

        return [
            'data' => $userData,
            'permissions' => $user->role->permissions->pluck('key'),
            'token' => $token,
        ];
    }
    public function logout(Request $request){
        $Agent = new Agent();
        $device = 'Desktop';
        if ( $Agent->isMobile() ) {
            $device = 'Mobile';
        }
        $user = Auth::user();
        $user->tokens()->where([
            ['tokenable_id',$user->id],
            ['name',$device]
        ])->delete();
        Auth::guard('web')->logout();
        return ["success" => true];
    }
}
