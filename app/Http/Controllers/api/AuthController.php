<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function __construct()
	{
//		Middleware que da acceso solo a login y register sin pedir token
		$this->middleware('auth:api', ['except' => ['login', 'register']]);
	}
	
	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');
		
		$token = Auth::attempt($credentials);
		if (!$token) {
			return response()->json([
				'status'  => 'error',
				'message' => 'Unauthorized',
			], 401);
		}
		
		$user = Auth::user();
		return response()->json([
			'status'        => 'success',
			'user'          => $user,
			'authorisation' => [
				'token' => $token,
				'type'  => 'bearer',
			]
		]);
		
	}
	
	public function me()
	{
		return response()->json(auth()->user());
	}
	
	public function register(Request $request)
	{
		$user = User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'password' => Hash::make($request->password),
		]);
		
		$token = Auth::login($user);

//		return response()->json([
//			'status'        => 'success',
//			'message'       => 'User created successfully',
//			'user'          => $user,
//			'authorisation' => [
//				'token' => $token,
//				'type'  => 'bearer',
//			]
//		]);
	}
	
	public function refresh()
	{
		return response()->json([
			'status'        => 'success',
			'user'          => Auth::user(),
			'authorisation' => [
				'token' => Auth::refresh(),
				'type'  => 'bearer',
			]
		]);
	}
	
	public function logout()
	{
		Auth::logout();
		
		return response()->json([
			'status'  => 'success',
			'message' => 'Successfully logged out',
		]);
	}
}
