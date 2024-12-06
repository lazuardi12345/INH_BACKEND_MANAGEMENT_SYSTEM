<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    // Menampilkan semua pengumuman
    public function index()
    {
        $pengumuman = Pengumuman::all();

        return response()->json([
            'data' => $pengumuman,
            'message' => 'Data pengumuman fetched successfully',
        ], 200);
    }

    // Menyimpan pengumuman baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Simpan file gambar ke penyimpanan publik
        $imagePath = $request->file('image')->store('pengumuman_images', 'public');

        // Simpan data ke database
        $pengumuman = Pengumuman::create([
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Pengumuman created successfully!',
            'pengumuman' => $pengumuman,
        ], 201);
    }

    // Menampilkan detail pengumuman
    public function show($id)
    {
        $pengumuman = Pengumuman::find($id);

        if (!$pengumuman) {
            return response()->json(['error' => 'Pengumuman not found'], 404);
        }

        return response()->json($pengumuman, 200);
    }

    // Memperbarui pengumuman
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::find($id);

        if (!$pengumuman) {
            return response()->json(['error' => 'Pengumuman not found'], 404);
        }

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($pengumuman->image && Storage::disk('public')->exists($pengumuman->image)) {
                Storage::disk('public')->delete($pengumuman->image);
            }
            $imagePath = $request->file('image')->store('pengumuman_images', 'public');
            $pengumuman->image = $imagePath;
        }

        $pengumuman->save();

        return response()->json([
            'message' => 'Pengumuman updated successfully!',
            'pengumuman' => $pengumuman,
        ], 200);
    }

    // Menghapus pengumuman
    public function destroy($id)
    {
        $pengumuman = Pengumuman::find($id);

        if (!$pengumuman) {
            return response()->json(['error' => 'Pengumuman not found'], 404);
        }

        if ($pengumuman->image && Storage::disk('public')->exists($pengumuman->image)) {
            Storage::disk('public')->delete($pengumuman->image);
        }

        $pengumuman->delete();

        return response()->json(['message' => 'Pengumuman deleted successfully'], 204);
    }
}
