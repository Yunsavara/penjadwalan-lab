<?php

namespace App\Http\Controllers\Admin;

use App\Models\roles;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class UsersController extends Controller
{
    public function index()
    {
        return view("admin.pengguna-page.pengguna", [
            'Lokasi' => new Lokasi(),
            'Peran' => new roles(),
            'page_meta' => [
                'page'=> 'Pengguna',
                'description' => 'Halaman untuk manajemen pengguna, peran dan lokasi.'
            ]
        ]);
    }

}
