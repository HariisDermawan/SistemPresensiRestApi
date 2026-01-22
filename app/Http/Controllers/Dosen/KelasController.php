<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    /**
     * List semua kelas yang diampu dosen login
     */
    public function index(Request $request)
    {
        $dosen = $request->user()->dosen; // ambil model Dosen dari user login
        $kelas = $dosen ? $dosen->kelas : collect();

        return response()->json([
            'message' => 'Daftar kelas yang diampu',
            'data' => $kelas->map(function($k){
                return [
                    'id' => $k->id,
                    'kode_kelas' => $k->kode_kelas,
                    'nama_kelas' => $k->nama_kelas,
                    'jurusan' => $k->jurusan
                ];
            })
        ]);
    }

    /**
     * Detail kelas tertentu
     */
    public function show($id, Request $request)
    {
        $dosen = $request->user()->dosen;
        if (!$dosen) {
            return response()->json(['message'=>'Dosen tidak ditemukan'], 404);
        }

        $kelas = $dosen->kelas()->find($id);
        if (!$kelas) {
            return response()->json(['message'=>'Kelas tidak ditemukan atau bukan kelas yang diampu dosen'], 404);
        }

        return response()->json([
            'message' => 'Detail kelas',
            'data' => [
                'id' => $kelas->id,
                'kode_kelas' => $kelas->kode_kelas,
                'nama_kelas' => $kelas->nama_kelas,
                'jurusan' => $kelas->jurusan
            ]
        ]);
    }

    /**
     * Attach kelas baru ke dosen login (Create)
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id'
        ]);

        $dosen = $request->user()->dosen;
        if (!$dosen) {
            return response()->json(['message'=>'Dosen tidak ditemukan'], 404);
        }

        $dosen->kelas()->syncWithoutDetaching([$request->kelas_id]);

        return response()->json([
            'message' => 'Kelas berhasil ditambahkan ke dosen',
            'data' => $dosen->kelas
        ]);
    }

    /**
     * Update kelas yang diampu (ganti kelas lama dengan kelas baru)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id'
        ]);

        $dosen = $request->user()->dosen;
        if (!$dosen) {
            return response()->json(['message'=>'Dosen tidak ditemukan'], 404);
        }

        // Lepas kelas lama dan pasang kelas baru
        $dosen->kelas()->detach($id);
        $dosen->kelas()->attach($request->kelas_id);

        return response()->json([
            'message' => 'Kelas berhasil diperbarui',
            'data' => $dosen->kelas
        ]);
    }

    /**
     * Hapus kelas dari dosen login (Detach)
     */
    public function destroy($kelas_id, Request $request)
{
    $dosen = $request->user()->dosen;
    if (!$dosen) {
        return response()->json(['message'=>'Dosen tidak ditemukan'], 404);
    }
    $dosen->kelas()->detach($kelas_id);

    return response()->json([
        'message' => 'Kelas berhasil dihapus dari dosen',
        'data' => $dosen->kelas->map(function($k){
            return [
                'id' => $k->id,
                'kode_kelas' => $k->kode_kelas,
                'nama_kelas' => $k->nama_kelas,
                'jurusan' => $k->jurusan
            ];
        })
    ]);
}

}
