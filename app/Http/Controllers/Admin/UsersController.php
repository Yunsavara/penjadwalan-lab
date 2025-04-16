<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\roles;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view("laboran.pengguna-page.pengguna", [
            'page_meta' => [
                'page'=> 'Pengguna',
                'description' => 'Halaman untuk manajemen pengguna dan peran.'
            ]
        ]);
    }

    public function getApiRoles(Request $request)
    {
        $query = Roles::select(['name', 'priority']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('priority', 'like', "%{$search}%");
            });
        }

        $totalData = Roles::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'asc';

        $columns = ['index', 'name', 'priority'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'name';

        // Hanya izinkan kolom DB untuk di-sort
        if (in_array($orderColumnName, ['name', 'priority'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            // Kolom tidak valid, bisa fallback atau diabaikan
            $query->orderBy('name', 'asc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $role) {
            $result[] = [
                'index' => $start + $index + 1,
                'name' => $role->name,
                'priority' => $role->priority,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result
        ]);
    }

}
