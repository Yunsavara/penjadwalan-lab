<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(){
        return view("admin.barang.barang", [
            'page_meta' => [
                'page' => 'BARANG'
            ]
        ]);
    }

    public function create(){
        return view("admin.barang.form", [
            'barang' => new Barang(),
            'page_meta' => [
                'page' => 'Tambah Barang',
                'method' => 'POST',
                'url' => route('admin.barang.create'),
                'button_text' => 'Tambah Barang'
            ]
        ]);
    }
}
