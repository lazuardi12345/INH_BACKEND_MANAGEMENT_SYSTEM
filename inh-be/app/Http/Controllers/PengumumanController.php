<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class PengumumanController extends Controller
{
    // Menampilkan semua pengumuman
    public function index()
    {
        try {
            $pengumuman = Pengumuman::all();

            return response()->json([
                'data' => $pengumuman,
                'message' => 'Data pengumuman fetched successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch pengumuman',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menyimpan pengumuman baru
    public function store(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to create pengumuman',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan detail pengumuman
    public function show($id)
    {
        try {
            $pengumuman = Pengumuman::find($id);

            if (!$pengumuman) {
                return response()->json(['error' => 'Pengumuman not found'], 404);
            }

            return response()->json($pengumuman, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch pengumuman details',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Memperbarui pengumuman
    public function update(Request $request, $id)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update pengumuman',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menghapus pengumuman
    public function destroy($id)
    {
        try {
            $pengumuman = Pengumuman::find($id);

            if (!$pengumuman) {
                return response()->json(['error' => 'Pengumuman not found'], 404);
            }

            if ($pengumuman->image && Storage::disk('public')->exists($pengumuman->image)) {
                Storage::disk('public')->delete($pengumuman->image);
            }

            $pengumuman->delete();

            return response()->json(['message' => 'Pengumuman deleted successfully'], 204);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to delete pengumuman',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
