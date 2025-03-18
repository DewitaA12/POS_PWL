<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //$user = UserModel::find(1); 
        //$user = UserModel::where('level_id', 1)->first();
        $user = UserModel::firstWhere('level_id', 1);
        return view('user', ['data' => $user]);
    }
}