<?php

namespace App\Http\Controllers\Admin;

use App\Models\roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Admin\Peran\PeranStoreRequest;
use App\Http\Requests\Admin\Peran\PeranUpdateRequest;

class RolesController extends Controller
{
    public function getApiPeran(Request $request)
    {
        $query = roles::select(['id','nama_peran', 'prioritas_peran']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_peran', 'like', "%{$search}%")
                    ->orWhere('prioritas_peran', 'like', "%{$search}%");
            });
        }

        $totalData = roles::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'desc';

        $columns = [null, 'nama_peran','id', 'prioritas_peran'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumnName, ['id','nama_peran', 'prioritas_peran'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination (limit data yang tampil per page)
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $peran) {
            $result[] = [
                // 'id' => $jenislab->id,
                'id_peran' => Crypt::encryptString($peran->id),
                'nama_peran' => $peran->nama_peran,
                'prioritas_peran' => $peran->prioritas_peran,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result
        ]);
    }

    public function store(PeranStoreRequest $Request) {
        // dd($Request->validated());

        DB::beginTransaction();

        try {

            $data = $Request->validated();

            roles::create([
                'nama_peran' => $data['nama_peran_store'],
                'prioritas_peran' => $data['prioritas_peran_store']
            ]);

            DB::commit();

            return redirect()->route('admin.pengguna')->with('success', 'Peran berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pengguna')->with('error', 'Peran Gagal ditambahkan');
        }
    }

    public function update(PeranUpdateRequest $Request, $id) {
        // dd($Request->validated());

        DB::beginTransaction();

        try {

            $data = $Request->validated();

            $Peran = roles::findOrFail(Crypt::decryptString($id));

            $Peran->update([
                'nama_peran' => $data['nama_peran_update'],
                'prioritas_peran' => $data['prioritas_peran_update']
            ]);

            DB::commit();

            return redirect()->route('admin.pengguna')->with('success', 'Peran Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pengguna')->with('error', 'Peran Gagal di-ubah');
        }
    }

    public function softDelete($id) {
        DB::beginTransaction();

        try {

            $Peran = roles::findOrFail(Crypt::decryptString($id));
            $Peran->delete();

            DB::commit();

            return redirect()->route('admin.pengguna')->with('success', 'Peran Berhasil di-hapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pengguna')->with('error', 'Peran Gagal di-hapus');
        }
    }
}
