<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
{
    // Ambil semua data news
    $news = News::all();

    // Kembalikan respons dalam format JSON dengan status 200
    return response()->json([
        'data' => $news,  // Mengembalikan data berita
        'message' => 'Data news fetched successfully', // Pesan status
    ], 200);
}


    // Menyimpan berita baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'deskripsi' => 'required|string',
        ]);
    
        // Simpan file gambar ke penyimpanan publik
        $imagePath = $request->file('image')->store('news_images', 'public');
    
        // Simpan data ke database
        $news = News::create([
            'name' => $validated['name'],
            'image' => $imagePath,
            'deskripsi' => $validated['deskripsi'],
        ]);
    
        return response()->json([
            'message' => 'News created successfully!',
            'news' => $news,
        ], 201);
    }
    
    // Menampilkan detail berita
    public function show($id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['error' => 'News not found'], 404);
        }

        return response()->json($news, 200);
    }

    // Memperbarui berita
    public function update(Request $request, $id)
    {
        $news = News::find($id);

        if (!$news) {
            return response()->json(['error' => 'News not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'deskripsi' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            if ($news->image && Storage::disk('public')->exists($news->image)) {
                Storage::disk('public')->delete($news->image);
            }
            $imagePath = $request->file('image')->store('news_images', 'public');
            $news->image = $imagePath;
        }

        $news->update([
            'name' => $validated['name'],
            'deskripsi' => $validated['deskripsi'],
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

        if ($news->image && Storage::disk('public')->exists($news->image)) {
            Storage::disk('public')->delete($news->image);
        }

        $news->delete();

        return response()->json(['message' => 'News deleted successfully'], 204);
    }
}
