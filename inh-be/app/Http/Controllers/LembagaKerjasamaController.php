<?php

namespace App\Http\Controllers;

use App\Models\LembagaKerjasama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LembagaKerjasamaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Mengambil semua data lembaga kerjasama
            $lembagaKerjasama = LembagaKerjasama::all();

            return response()->json([
                'data' => $lembagaKerjasama,
                'message' => 'Lembaga Kerjasama retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve Lembaga Kerjasama',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, LembagaKerjasama $lembagaKerjasama)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);
    
            // Update kolom yang ada di request
            if ($request->has('name')) {
                $lembagaKerjasama->name = $validated['name'];
            }
    
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($lembagaKerjasama->image && file_exists(public_path('storage/' . $lembagaKerjasama->image))) {
                    unlink(public_path('storage/' . $lembagaKerjasama->image));
                }
    
                // Simpan gambar baru
                $imagePath = $request->file('image')->store('lembaga_kerjasama_images', 'public');
                $lembagaKerjasama->image = $imagePath;
            }
    
            // Simpan perubahan
            $lembagaKerjasama->save();
    
            return response()->json([
                'message' => 'Lembaga Kerjasama updated successfully!',
                'lembagaKerjasama' => $lembagaKerjasama,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Lembaga Kerjasama',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    

    /**
     * Display the specified resource.
     */
    public function show(LembagaKerjasama $lembagaKerjasama)
    {
        try {
            return response()->json([
                'data' => $lembagaKerjasama,
                'message' => 'Lembaga Kerjasama retrieved successfully',
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve Lembaga Kerjasama',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LembagaKerjasama $lembagaKerjasama)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            // Update nama
            $lembagaKerjasama->name = $validated['name'];

            // Jika ada gambar baru
            if ($request->hasFile('image')) {
                // Hapus gambar lama
                if (file_exists(public_path('storage/' . $lembagaKerjasama->image))) {
                    unlink(public_path('storage/' . $lembagaKerjasama->image));
                }

                // Simpan file gambar ke penyimpanan publik
                $imagePath = $request->file('image')->store('lembaga_kerjasama_images', 'public');
                $lembagaKerjasama->image = $imagePath;
            }

            // Simpan perubahan
            $lembagaKerjasama->save();

            return response()->json([
                'message' => 'Lembaga Kerjasama updated successfully!',
                'lembagaKerjasama' => $lembagaKerjasama,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Failed to update Lembaga Kerjasama',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LembagaKerjasama $lembagaKerjasama)
    {
        try {
            // Hapus gambar jika ada
            if (file_exists(public_path('storage/' . $lembagaKerjasama->image))) {
                unlink(public_path('storage/' . $lembagaKerjasama->image));
            }

            // Hapus data dari database
            $lembagaKerjasama->delete();

            return response()->json([
                'message' => 'Lembaga Kerjasama deleted successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Failed to delete Lembaga Kerjasama',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
