<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
		
		if (!$token = auth()->attempt($credentials)) {
			return response()->json([
				'status'  => 'error',
				'message' => 'Unauthorized',
			], 401);
		}
		
		return response()->json([
			'status' => 'success',
			'user'   => auth()->user(),
			'token'  => $token
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
		
		return response()->json([
			'status'  => 'success',
			'message' => 'User created successfully',
			'user'    => $user,
		]);
	}
	
	public function refresh()
	{
		return response()->json([
			'status'   => 'success',
			'newToken' => auth()->refresh(),
		]);
	}
	
	public function logout()
	{
		auth()->logout();
		
		return response()->json([
			'status'  => 'success',
			'message' => 'Successfully logged out',
		]);
	}
}