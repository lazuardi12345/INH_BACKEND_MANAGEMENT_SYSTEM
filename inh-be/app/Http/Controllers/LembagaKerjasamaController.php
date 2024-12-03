<?php

namespace App\Http\Controllers;

use App\Models\LembagaKerjasama;
use Illuminate\Http\Request;

class LembagaKerjasamaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lembagaKerjasama = LembagaKerjasama::all();
        return response()->json($lembagaKerjasama);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Simpan file gambar ke penyimpanan publik
        $imagePath = $request->file('image')->store('lembaga_kerjasama_images', 'public');

        // Simpan data ke database
        $lembagaKerjasama = LembagaKerjasama::create([
            'name' => $validated['name'],
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Lembaga Kerjasama created successfully!',
            'lembagaKerjasama' => $lembagaKerjasama,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LembagaKerjasama $lembagaKerjasama)
    {
        return response()->json($lembagaKerjasama);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LembagaKerjasama $lembagaKerjasama)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LembagaKerjasama $lembagaKerjasama)
    {
        // Hapus gambar jika ada
        if (file_exists(public_path('storage/' . $lembagaKerjasama->image))) {
            unlink(public_path('storage/' . $lembagaKerjasama->image));
        }

        // Hapus data dari database
        $lembagaKerjasama->delete();

        return response()->json([
            'message' => 'Lembaga Kerjasama deleted successfully!',
        ]);
    }
}
