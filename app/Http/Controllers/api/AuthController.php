<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$credentials = $request->only('email', 'password');
		
		if (!$token = auth()->attempt($credentials)) {
			return response()->json([
				'status'  => 'error',
				'message' => 'Unauthorized',
			], Response::HTTP_UNAUTHORIZED);
		}
		
		return response()->json([
			'status' => 'success',
			'user'   => auth()->user(),
			'token'  => $token
		], Response::HTTP_OK);
		
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
		], Response::HTTP_OK);
	}
	
	public function refresh()
	{
		return response()->json([
			'status'   => 'success',
			'newToken' => auth()->refresh(),
		], Response::HTTP_OK);
	}
	
	public function logout()
	{
		auth()->logout();
		
		return response()->json([
			'status'  => 'success',
			'message' => 'Successfully logged out',
		], Response::HTTP_OK);
	}
}