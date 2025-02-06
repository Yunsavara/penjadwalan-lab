<?php

namespace App\Http\Controllers\Admin;

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
}
