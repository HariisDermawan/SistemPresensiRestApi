<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\SiswaResource;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = Siswa::with('kelas')->get();
        return SiswaResource::collection($siswas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required|string|unique:siswas,nim',
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'kelas_id' => 'required|exists:kelas,id',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $siswa = Siswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'nama' => $request->nama,
            'email' => $request->email,
            'kelas_id' => $request->kelas_id,
        ]);

        return response()->json([
            'message' => 'Siswa berhasil dibuat',
            'data' => new SiswaResource($siswa)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::find($id);
        if (!$siswa) {
            return response()->json([
                'message' => 'siswa tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Details Data Mahasiswa',
            'data' => new SiswaResource($siswa)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);
        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $request->validate([
            'nim' => 'sometimes|string|unique:siswas,nim,' . $siswa->id,
            'nama' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $siswa->user_id,
            'kelas_id' => 'sometimes|exists:kelas,id',
            'password' => 'sometimes|string|min:6'
        ]);

        $siswa->user->update([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $siswa->user->password
        ]);

        $siswa->update([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'email' => $request->email,
            'kelas_id' => $request->kelas_id
        ]);

        return response()->json([
            'message' => 'Siswa berhasil diperbarui',
            'data' => new SiswaResource($siswa)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::find($id);
        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }
        $siswa->user->delete();
        $siswa->delete();

        return response()->json([
            'message' => 'Siswa berhasil dihapus'
        ]);
    }
}
