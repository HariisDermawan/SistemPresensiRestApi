<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Resources\PresensiResource;
use App\Models\Presensi;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $siswa = $request->user()->siswa;

    if (!$siswa) {
        return response()->json([
            'message' => 'Siswa tidak ditemukan'
        ], 404);
    }

    $data = $siswa->presensis()
        ->with('dosen', 'siswa.kelas')
        ->get()
        ->map(function ($p) {
            return [
                'id' => $p->id,
                'tanggal' => $p->tanggal,
                'status' => $p->status,
                'keterangan' => $p->keterangan,
                'kelas' => [
                    'id' => $p->siswa->kelas->id,
                    'nama_kelas' => $p->siswa->kelas->nama_kelas,
                ],
                'dosen' => [
                    'id' => $p->dosen->id,
                    'nama' => $p->dosen->nama,
                ],
            ];
        });

    return response()->json([
        'message' => 'Presensi siswa',
        'data' => $data
    ]);
}




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'status' => 'required|in:Hadir,Izin,Alpha,Sakit',
        'tanggal' => 'required|date',
        'keterangan' => 'nullable|string'
    ]);

    $user = $request->user();
    $siswa = $user->siswa;

    if (!$siswa) {
        return response()->json([
            'message' => 'Siswa tidak ditemukan'
        ], 404);
    }

    // dosen diambil dari relasi kelas siswa
    $dosen = $siswa->kelas
        ->dosens()
        ->first();

    if (!$dosen) {
        return response()->json([
            'message' => 'Dosen tidak ditemukan untuk kelas ini'
        ], 404);
    }

    $presensi = $siswa->presensis()->create([
        'dosen_id' => $dosen->id,
        'tanggal' => $request->tanggal,
        'status' => $request->status,
        'keterangan' => $request->keterangan,
    ]);

    return response()->json([
        'message' => 'Presensi berhasil',
        'data' => $presensi
    ], 201);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
