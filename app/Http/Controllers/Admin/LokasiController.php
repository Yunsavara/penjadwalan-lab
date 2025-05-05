<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Admin\Lokasi\LokasiStoreRequest;
use App\Http\Requests\Admin\Lokasi\LokasiUpdateRequest;

class LokasiController extends Controller
{
    // Index Nya ada di UsersController, karena pakai modal untuk formnya jadi gk pindah halaman

    public function getApiLokasi(Request $request) {
        $query = Lokasi::select(['id','nama_lokasi', 'deskripsi_lokasi']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_lokasi', 'like', "%{$search}%")
                    ->orWhere('deskripsi_lokasi', 'like', "%{$search}%");
            });
        }

        $totalData = Lokasi::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'desc';

        $columns = [null, 'nama_lokasi','id', 'deskripsi_lokasi'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumnName, ['id','nama_lokasi', 'deskripsi_lokasi'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination (limit data yang tampil per page)
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $lokasi) {
            $result[] = [
                // 'id' => $jenislab->id,
                'id_lokasi' => Crypt::encryptString($lokasi->id),
                'nama_lokasi' => $lokasi->nama_lokasi,
                'deskripsi_lokasi' => $lokasi->deskripsi_lokasi,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result
        ]);
    }

    public function store(LokasiStoreRequest $Request) {
        // dd($Request->validated());

        DB::beginTransaction();
        try {

            $data = $Request->validated();

            Lokasi::create([
                'nama_lokasi' => $data['nama_lokasi_store'],
                'deskripsi_lokasi' => $data['deskripsi_lokasi_store']
            ]);

            DB::commit();

            return redirect()->route('admin.pengguna')->with('success', 'Lokasi Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pengguna')->with('error', 'Lokasi Gagal ditambahkan <br>'. $e->getMessage());
        }
    }

    public function update(LokasiUpdateRequest $Request, $id) {
        // dd($Request->validated());

        DB::beginTransaction();
        try {

            $data = $Request->validated();

            $Lokasi = Lokasi::findOrFail(Crypt::decryptString($id));

            $Lokasi->update([
                'nama_lokasi' => $data['nama_lokasi_update'],
                'deskripsi_lokasi' => $data['deskripsi_lokasi_update']
            ]);

            DB::commit();

            return redirect()->route('admin.pengguna')->with('success', 'Lokasi Berhasil di-ubah');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pengguna')->with('error', 'Lokasi Gagal di-ubah<br>' . $e->getMessage());
        }
    }

    public function softDelete($id) {
        DB::beginTransaction();

        try {

            $Lokasi = Lokasi::findOrFail(Crypt::decryptString($id));
            $Lokasi->delete();

            DB::commit();

            return redirect()->route('admin.pengguna')->with('success', 'Lokasi Berhasil di-hapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pengguna')->with('error', 'Lokasi Gagal di-hapus');
        }
    }
}
