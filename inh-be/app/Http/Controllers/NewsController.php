<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // Menampilkan semua berita
    public function index()
    {
        try {
            $news = News::all();
            return response()->json([
                'data' => $news,
                'message' => 'Data news fetched successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch news',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menyimpan berita baru
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
                'deskripsi' => 'required|string',
                'author' => 'required|string|max:255',
            ]);

            // Simpan file gambar ke penyimpanan publik
            $imagePath = $request->file('image')->store('news_images', 'public');

            // Simpan data ke database
            $news = News::create([
                'title' => $validated['title'],
                'image' => $imagePath,
                'deskripsi' => $validated['deskripsi'],
                'author' => $validated['author'],
            ]);

            return response()->json([
                'message' => 'News created successfully!',
                'news' => $news,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create news',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan detail berita berdasarkan ID
    public function show($id)
    {
        try {
            $news = News::find($id);

            if (!$news) {
                return response()->json(['error' => 'News not found'], 404);
            }

            return response()->json([
                'data' => $news,
                'message' => 'News detail fetched successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch news detail',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    // Memperbarui berita
public function update(Request $request, $id)
{
    try {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['error' => 'News not found'], 404);
        }

        // Validasi input dengan kondisi 'nullable' pada author
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'deskripsi' => 'nullable|string', // Menjadikan deskripsi nullable
            'author' => 'nullable|string|max:255', // Menjadikan author nullable
        ]);

        // Jika ada file baru, hapus file lama dan simpan file baru
        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
            $news->image = $imagePath;
        }

        // Perbarui data lainnya, jika author tidak diisi, tetap simpan nilai lama
        $news->update([
            'title' => $validated['title'],
            'deskripsi' => $validated['deskripsi'] ?? $news->deskripsi, // Jika deskripsi tidak ada, gunakan nilai lama
            'author' => $validated['author'] ?? $news->author, // Jika author tidak ada, gunakan nilai lama
        ]);

        return response()->json([
            'message' => 'News updated successfully!',
            'news' => $news,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to update news',
            'message' => $e->getMessage(),
        ], 500);
    }
}



    // Menghapus berita
    public function destroy($id)
    {
        try {
            $news = News::find($id);

            if (!$news) {
                return response()->json(['error' => 'News not found'], 404);
            }

            // Hapus file gambar jika ada
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }

            $news->delete();

            return response()->json(['message' => 'News deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete news',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
