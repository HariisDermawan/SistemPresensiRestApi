<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Presensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PresensiController extends Controller
{
    /** 
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dosen = $request->user()->dosen;
        $presensi = $dosen
            ? Presensi::where('dosen_id', $dosen->id)->get()
            : Presensi::all();

        return response()->json([
            'message' => 'List presensi',
            'data' => $presensi->map(function ($p) {
                return [
                    'id' => $p->id,
                    'kelas_id' => $p->kelas_id,
                    'siswa_id' => $p->siswa_id,
                    'dosen_id' => $p->dosen_id,
                    'tanggal' => $p->tanggal,
                    'status' => $p->status
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $dosen = $request->user()->dosen;
        if (!$dosen) {
            return response()->json(['message' => 'Dosen tidak ditemukan'], 404);
        }

        // Validasi input
        $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'siswa_id' => 'required|integer|exists:siswas,id',  // sesuai tabel siswas
            'status'   => 'required|in:hadir,izin,sakit,alfa',
            'tanggal'  => 'required|date',
        ]);

        // Pastikan dosen memang mengampu kelas ini
        if (!$dosen->kelas()->where('kelas.id', $request->kelas_id)->exists()) {
            return response()->json([
                'message' => 'Dosen tidak mengampu kelas ini'
            ], 403);
        }

        // Create presensi
        $presensi = Presensi::create([
            'kelas_id' => $request->kelas_id,
            'siswa_id' => $request->siswa_id,
            'dosen_id' => $dosen->id,
            'status'   => $request->status,
            'tanggal'  => $request->tanggal,
        ]);

        // Hide created_at & updated_at
        return response()->json([
            'message' => 'Presensi berhasil dibuat',
            'data' => $presensi->makeHidden(['created_at', 'updated_at'])
        ], 201);
    }

    public function show($id)
    {
        $presensi = Presensi::find($id);
        if (!$presensi) return response()->json(['message' => 'Presensi tidak ditemukan'], 404);

        return response()->json([
            'message' => 'Detail presensi',
            'data' => $presensi
        ]);
    }

    public function update(Request $request, $id)
    {
        $presensi = Presensi::find($id);
        if (!$presensi) return response()->json(['message' => 'Presensi tidak ditemukan'], 404);

        $request->validate([
            'status' => 'sometimes|in:hadir,izin,sakit,alfa',
            'tanggal' => 'sometimes|date'
        ]);

        $presensi->update($request->only(['status', 'tanggal']));

        return response()->json([
            'message' => 'Presensi berhasil diupdate',
            'data' => $presensi
        ]);
    }

    public function destroy($id)
    {
        $presensi = Presensi::find($id);
        if (!$presensi) return response()->json(['message' => 'Presensi tidak ditemukan'], 404);

        $presensi->delete();

        return response()->json([
            'message' => 'Presensi berhasil dihapus'
        ]);
    }
}
