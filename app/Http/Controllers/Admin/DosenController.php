<?php

namespace App\Http\Controllers\Admin;

use App\Models\Dosen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Routing\Route;
use App\Http\Resources\DosenResource;
use Symfony\Component\HttpFoundation\Response;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dosen = Dosen::with('kelas')->get();
        return DosenResource::collection($dosen);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nidn' => 'required|unique:dosens,nidn,',
            'nama' => 'required|string',
            'email' => 'required|email|unique:dosens,email,'
        ]);

        $dosen = Dosen::create($request->all());

        return response()->json([
            'message' => 'Dosen Berhasil dibuat',
            'data' => new DosenResource($dosen)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json([
                'message' => 'Data Dosen Tidak Ditemukan!',
            ], 400);
        }
        return response()->json([
            'message' => 'Detail Data Dosen',
            'data' => new DosenResource($dosen)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json([
                'message' => 'Data Dosen Tidak di Temukan!'
            ], 404);
        }
        $request->validate([
            'nidn' => 'sometimes|unique:dosens,nidn,' . $dosen->id,
            'nama' => 'sometimes|string',
            'email' => 'required|email|unique:dosens,email,' . $dosen->id
        ]);

        if ($dosen->user) {
            $dosen->user->update([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $dosen->user->password
            ]);

            $dosen->update([
                'nidn' => $request->nidn,
                'nama' => $request->nama,
                'email' => $request->email,
            ]);

            return response()->json([
                'message' => 'Dosen berhasil diperbarui',
                'data' => new DosenResource($dosen)
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json([
                'message' => 'Dosen Tidak di Temukan!'
            ], 404);
        }

        $dosen->delete();

        return response()->json([
            'message' => 'Dosen Berhasil dihapus!'
        ]);
    }
}
