<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\KelasResource;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelas = Kelas::all();
        return KelasResource::collection($kelas);
    }

    /** 
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_kelas' => 'required|string|unique:kelas,kode_kelas,',
            'nama_kelas' => 'required|string',
            'jurusan'    => 'required|string',
        ]);
        $kelas = Kelas::create($request->all());

        return response()->json([
            'message' => 'Kelas Berhasil Dibuat!',
            'data' => new KelasResource($kelas)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kelas = Kelas::find($id);
        if (!$kelas) {
            return response()->json(['message' => 'Kelas tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail Kelas',
            'data' => new KelasResource($kelas)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kelas = Kelas::find($id);
        if (!$kelas) {
            return response()->json([
                'message' => 'kelas tidak ditemukan!',
            ], 404);
        }
        $request->validate([
            'kode_kelas' => 'sometimes|string|unique:kelas,kode_kelas,' . $kelas->id,
            'nama_kelas' => 'sometimes|string',
            'jurusan'    => 'sometimes|string',
        ]);

        $kelas->update($request->all());

        return response()->json([
            'message' => 'Data Kelas Berhasil Diperbaruhi!',
            'data' => new KelasResource($kelas)
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::find($id);
        if (!$kelas) {
            return response()->json([
                'message' => 'Kelas tidak ditemukan!',
            ], 404);
        }
        $kelas->delete();
        return response()->json([
            'message' => 'Kelas berhasil dihapus!',
        ]);
    }
}
