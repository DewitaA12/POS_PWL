<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        //Retrieving Single Models
        //$user = UserModel::find(1); 
        // //$user = UserModel::where('level_id', 1)->first();
        // // $user = UserModel::firstWhere('level_id', 1);
        // $user = UserModel::findOr(20,['username', 'nama'], function(){
        //     abort(404);
        // });

        //Not Found Exceptions
        //$user = UserModel::findOrFail(1);
        //$user = UserModel::where('username', 'manager9')->firstorFail();

        //Retrieving Agregrates
        $user = UserModel::where('level_id',2)->count();
        dd($user);
        return view('user', ['data' => $user]);
    }
}