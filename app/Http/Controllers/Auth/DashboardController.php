<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(){
        return view("admin.index");
    }

    public function laboran(){
        return view("laboran.index");
    }

    public function user(){
        return view("user.index");
    }
}
