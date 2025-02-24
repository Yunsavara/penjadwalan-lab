<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PengajuanController extends Controller
{
    public function index(){
        return view('laboran.pengajuan.pengajuan', [
            'page_meta' => [
                'page' => 'Pengajuan'
            ]
        ]);
    }

    // Datatables
    public function getData(Request $request)
    {
        // Query untuk mengambil data unik berdasarkan kode_pengajuan
        $query = Pengajuan::select([
                    'kode_pengajuan',
                    DB::raw('GROUP_CONCAT(DISTINCT tanggal ORDER BY tanggal ASC SEPARATOR ", ") as tanggal_pengajuan'),
                    'keperluan',
                    'status',
                    'lab_id',
                    'user_id'
                ])
                ->groupBy('kode_pengajuan', 'keperluan', 'status', 'lab_id', 'user_id');

        // Filter pencarian
        if ($search = $request->input('search.value')) {
            $query->having('kode_pengajuan', 'like', "%$search%")
                ->orHaving('keperluan', 'like', "%$search%")
                ->orHaving('tanggal_pengajuan', 'like', "%$search%");
        }

        // Pagination
        $data = $query->paginate($request->input('length'));

        // Total record untuk pagination
        $recordsTotal = Pengajuan::distinct('kode_pengajuan')->count();
        $recordsFiltered = $query->count();

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data->items(),
        ]);
    }
}
