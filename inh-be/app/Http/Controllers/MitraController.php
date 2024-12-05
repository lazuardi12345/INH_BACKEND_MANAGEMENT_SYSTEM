<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    
    public function index()
{
    $mitras = Mitra::all()->map(function ($mitra) {
        $mitra->image = $mitra->image ? asset('storage/' . $mitra->image) : null;
        return $mitra;
    });

    return response()->json($mitras, 200);
}

    // Menyimpan mitra baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
    
        // Simpan file gambar ke penyimpanan publik
        $imagePath = $request->file('image')->store('mitra_images', 'public');
    
        // Simpan data ke database
        $mitra = Mitra::create([
            'name' => $validated['name'],
            'image' => $imagePath,
        ]);
    
        return response()->json([
            'message' => 'Mitra created successfully!',
            'mitra' => $mitra,
        ], 201);
    }
    
    // Menampilkan detail mitra
    public function show($id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['error' => 'Mitra not found'], 404);
        }

        return response()->json($mitra, 200);
    }

    // Memperbarui mitra
    public function update(Request $request, $id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['error' => 'Mitra not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($mitra->image && Storage::disk('public')->exists($mitra->image)) {
                Storage::disk('public')->delete($mitra->image);
            }
            $imagePath = $request->file('image')->store('mitra_images', 'public');
            $mitra->image = $imagePath;
        }

        $mitra->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Mitra updated successfully!',
            'mitra' => $mitra,
        ], 200);
    }

    // Menghapus mitra
    public function destroy($id)
    {
        $mitra = Mitra::find($id);

        if (!$mitra) {
            return response()->json(['error' => 'Mitra not found'], 404);
        }

        if ($mitra->image && Storage::disk('public')->exists($mitra->image)) {
            Storage::disk('public')->delete($mitra->image);
        }

        $mitra->delete();

        return response()->json(['message' => 'Mitra deleted successfully'], 204);
    }
}
