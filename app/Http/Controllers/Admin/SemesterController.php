<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index(){
        return view('admin.semester.semester', [
            'page_meta' => [
                'page' => 'Semester'
            ]
        ]);
    }
}
