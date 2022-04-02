<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Services\Api\V1\AuthTypeServicesRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $registry;

    public function __construct(AuthTypeServicesRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function login() {
        validator(request()->all(), [
            'email' => 'required|string',
            'password' => 'required'
        ])->validate();

        $user = $this->registry->get(request('type'))
            ->findByEmail(request('email'));

        if (!$user || !Hash::check(request('password'), $user->getAuthPassword())){
            return response()->json(['message' => 'Wrong Credential'], 401);
        }

        return response()->success(['access-token' => $user->createToken(time(), [request('type').'-apis'])->plainTextToken]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->success(['message' => 'Logout Successfully']);
    }

    public function register() {
        $data = request()->all();

        $user = $this->registry->get(request('type'));
        $this->validator($data, $user->customRules());
        $user = $user->create($data);

        return response()->success(['message' => 'Registered Successfully']);
    }

    private function validator($data, $customRules) {
        validator()->make($data, array_merge([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], $customRules))->validate();
    }
}
