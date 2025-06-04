<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LevelModel;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
   public function login()
    {
        if(Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/welcome')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

    public function register()
    {
    $level = LevelModel::all(); // ambil semua level dari tabel m_level
    return view('auth.register', compact('level'));
    }

    public function store(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'username' => 'required|unique:m_user,username',
        'nama' => 'required|string|max:100',
        'password' => 'required|min:6',
        'level_id' => 'required|exists:m_level,level_id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    \App\Models\UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => bcrypt($request->password),
        'level_id' => $request->level_id,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'User berhasil diregistrasi',
        'redirect' => url('/user')
    ]);
    }

}
