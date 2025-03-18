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
        // $user = UserModel::where('level_id',2)->count();
        // dd($user);

        //Retrieving or Creating Model
        // $user = UserModel :: firstOrCreate(
        //     [
        //     'username' => 'manager',
        //     'nama' => 'Manager',
        //     ],
        // );

        $user = UserModel :: firstOrCreate(
            [
                'username' => 'manager22',
                'nama' => 'Manager Dua Dua',
                'password' => Hash::make('12345'),
                'level_id' => 2
             ],
        );

        return view('user', ['data' => $user]);
    }

    public function jumlahPengguna()
    {
        $jumlahPengguna = UserModel::where('level_id', 2)->count();
        return view('jumlahUser', ['jumlahPengguna' => $jumlahPengguna]);
    }
}