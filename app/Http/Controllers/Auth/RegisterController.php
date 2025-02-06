<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{
    public function index(){
        return view("auth.register", [
            'register' => new User(),
            'page_meta' => [
                'method' => 'POST',
                'url' => route('register'),
            ]
        ]);
    }

    public function store(RegisterRequest $request){
        // dd($request->all());

        DB::beginTransaction();
        try {

            $register = User::create([
                'name' => $request->input('nama_lengkap'),
                'email' => $request->input('user_email'),
                'password' => $request->input('password')
            ]);

            Auth::login($register);

            DB::commit();

            // return response()->json([
            //     'message' => 'Data berhasil terinput',
            //     'data' => $register
            // ], 201);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back();

            // return response()->json([
            //     'message' => 'Data gagal terinput',
            //     'error' => $e->getMessage()
            // ], 500);
        }
    }
}
