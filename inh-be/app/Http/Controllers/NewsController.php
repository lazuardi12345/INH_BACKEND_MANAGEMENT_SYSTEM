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
        $news = News::all();

        return response()->json([
            'data' => $news,
            'message' => 'Data news fetched successfully',
        ], 200);
    }

    // Menyimpan berita baru
    public function store(Request $request)
    {
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
    }

    // Menampilkan detail berita berdasarkan ID
    public function show($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['error' => 'News not found'], 404);
        }

        return response()->json([
            'data' => $news,
            'message' => 'News detail fetched successfully',
        ], 200);
    }

    // Memperbarui berita
    public function update(Request $request, $id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['error' => 'News not found'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'deskripsi' => 'required|string',
            'author' => 'required|string|max:255',
        ]);

        // Jika ada file baru, hapus file lama dan simpan file baru
        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
            $news->image = $imagePath;
        }

        // Perbarui data lainnya
        $news->update([
            'title' => $validated['title'],
            'deskripsi' => $validated['deskripsi'],
            'author' => $validated['author'],
        ]);

        return response()->json([
            'message' => 'News updated successfully!',
            'news' => $news,
        ], 200);
    }

    // Menghapus berita
    public function destroy($id)
    {
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
    }
}
