<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(){
        return view("admin.index", [
            'page_meta' => [
                'page' => 'Dashboard'
            ]
        ]);
    }

    public function laboran(){
        return view("laboran.index", [
            'page_meta' => [
                'page' => 'Dashboard'
            ]
        ]);
    }

    public function dashboardPengguna(){
        return view("pengguna.index", [
            'page_meta' => [
                'page' => 'Dashboard'
            ]
        ]);
    }
}
