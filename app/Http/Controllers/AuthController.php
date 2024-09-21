<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;    
    }

    public function showSignupForm(){
        return view('auth.signup');
    }

    public function showLoginForm(){
        return view('auth.login');
    }

    public function signup(SignupRequest $request){
        $data = $request->validated();

        $data['role'] = 'super_admin';
        $data['password'] = bcrypt($data['password']);

        $this->userService->createUser($data);

        return redirect()->route('login')->with('success', 'User registered successfully.');
    }

    public function login(LoginRequest $request){
        $user = $this->userService->findUser(
            columns:['id,email,password'],
            condition:'email = ?',
            bindings:[$request->email],
            hidden:[]
        );
            
        if ($user && Hash::check($request->password, $user['password'])) {
            Auth::loginUsingId($user['id']);
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request){
        Session::flush();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
