<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $siswa = $user->siswa;

        if (!$siswa) {
            return response()->json([
                'message' => 'Siswa tidak ditemukan untuk user login ini'
            ], 404);
        }

        return response()->json([
            'message' => 'Profil siswa',
            'data' => [
                'id'    => $siswa->id,
                'nim'   => $siswa->nim,
                'nama'  => $siswa->nama,
                'email' => $siswa->email,
                'kelas' => [
                    'id'         => $siswa->kelas->id,
                    'kode_kelas' => $siswa->kelas->kode_kelas,
                    'nama_kelas' => $siswa->kelas->nama_kelas,
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $siswa = $request->user()->siswa;

        if (!$siswa) {
            return response()->json([
                'message' => 'Profil siswa belum dibuat'
            ], 404);
        }

        $request->validate([
            'nama'  => 'required|string',
            'email' => 'required|email|unique:siswas,email,' . $siswa->id
        ]);

        $siswa->update([
            'nama'  => $request->nama,
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'id'    => $siswa->id,
                'nama'  => $siswa->nama,
                'email' => $siswa->email
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $siswa = $request->user()->siswa;

        if (!$siswa) {
            return response()->json([
                'message' => 'Profil siswa tidak ditemukan'
            ], 404);
        }

        $siswa->delete();

        return response()->json([
            'message' => 'Profil siswa berhasil dihapus'
        ]);
    }
}
