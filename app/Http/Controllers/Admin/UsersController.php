<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\roles;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Models\LaboratoriumUnpam;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\Admin\Pengguna\PenggunaStoreRequest;
use App\Http\Requests\Admin\Pengguna\PenggunaUpdateRequest;

class UsersController extends Controller
{
    public function index()
    {

        $LokasiFormSelect = Lokasi::select('id','nama_lokasi')->get();
        $PeranFormSelect = roles::select('id','nama_peran')->get();

        return view("admin.pengguna-page.pengguna", [
            'Pengguna' => new User(),
            'Peran' => new roles(),
            'Lokasi' => new Lokasi(),
            'LokasiFormSelect' => $LokasiFormSelect,
            'PeranFormSelect' => $PeranFormSelect,
            'page_meta' => [
                'page'=> 'Pengguna',
                'description' => 'Halaman untuk manajemen pengguna, peran dan lokasi.'
            ]
        ]);
    }

    public function getApiPengguna(Request $request){
        $query = User::select(['id','nama_pengguna', 'email', 'lokasi_id', 'role_id']);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_pengguna', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('lokasi_id', 'like', "%{$search}%")
                    ->orWhere('role_id', 'like', "%{$search}%");
            });
        }

        $totalData = User::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'desc';

        $columns = [null, 'nama_pengguna','id', 'email', 'lokasi_id', 'role_id'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumnName, ['id','nama_pengguna', 'email', 'lokasi_id', 'role_id'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination (limit data yang tampil per page)
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $pengguna) {
            $result[] = [
                // 'id' => $jenislab->id,
                'id_pengguna' => Crypt::encryptString($pengguna->id),
                'nama_pengguna' => $pengguna->nama_pengguna,
                'email' => $pengguna->email,
                'lokasi_id' => $pengguna->lokasi_id,
                'role_id' => $pengguna->role_id,
                'nama_lokasi' => $pengguna->lokasi->nama_lokasi,
                'nama_peran' => $pengguna->role->nama_peran
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result
        ]);
    }

    public function store(PenggunaStoreRequest $Request) {
        // dd($Request->validated());

        DB::beginTransaction();

        try {

            $data = $Request->validated();

            User::create([
                'nama_pengguna' => $data['nama_pengguna_store'],
                'email' => $data['email_pengguna_store'],
                'password' => Hash::make($data['password_pengguna_store']),
                'lokasi_id' => $data['lokasi_id_store'],
                'role_id' => $data['peran_id_store']
            ]);

            DB::commit();

            return redirect()->route('admin.pengguna')->with('success', 'Pengguna Berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.pengguna')->with('error', 'Pengguna Gagal ditambahkan <br>' . $e->getMessage());
        }
    }

    public function update(PenggunaUpdateRequest $Request, $id) {
        // dd($Request->validated());

        DB::beginTransaction();

        try {

            $data = $Request->validated();

            $Pengguna = User::findOrFail(Crypt::decryptString($id));

            $updateData = [
                'nama_penguna' => $data['nama_pengguna_update'],
                'email' => $data['email_pengguna_update'],
                'lokasi_id' => $data['lokasi_id_update'],
                'role_id' => $data['peran_id_update'],
            ];

            // Update password hanya jika diisi
            if (!empty($data['password_pengguna_update'])) {
                $updateData['password'] = Hash::make($data['password_pengguna_update']);
            }

            $Pengguna->update($updateData);

            DB::commit();

            return redirect()->route('admin.pengguna')->with('success', 'Pengguna Berhasil di-ubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.pengguna')->with('error', 'Pengguna Gagal di-ubah');
        }
    }
}
