<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
{
    // Mengambil semua data campaign
    $campaigns = Campaign::all();

    return response()->json([
        'data' => $campaigns,
        'message' => 'Campaigns retrieved successfully',
    ], 200);
}


    // Menyimpan campaign baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Simpan file gambar ke penyimpanan publik
        $imagePath = $request->file('image')->store('campaign_images', 'public');

        // Simpan data ke database
        $campaign = Campaign::create([
            'name' => $validated['name'],
            'deskripsi' => $validated['deskripsi'],
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Campaign created successfully!',
            'campaign' => $campaign,
        ], 201); // Status 201 untuk Created
    }

    // Menampilkan detail campaign
    public function show($id)
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        return response()->json($campaign, 200);
    }

    // Memperbarui campaign
    public function update(Request $request, $id)
    {
        // Cari campaign berdasarkan ID
        $campaign = Campaign::find($id);
    
        // Jika tidak ditemukan, kembalikan respons error
        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }
    
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // 'nullable' jika tidak ada gambar baru
        ]);
    
        // Jika ada file gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($campaign->image && \Storage::disk('public')->exists($campaign->image)) {
                \Storage::disk('public')->delete($campaign->image);
            }
    
            // Simpan gambar baru ke penyimpanan publik
            $imagePath = $request->file('image')->store('campaign_images', 'public');
            $campaign->image = $imagePath; // Perbarui path gambar di database
        }
    
        // Perbarui data lainnya
        $campaign->update([
            'name' => $validated['name'],
            'deskripsi' => $validated['deskripsi'],
        ]);
    
        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Campaign updated successfully!',
            'campaign' => $campaign,
        ], 200); // Status 200 untuk sukses
    }
    
    
    

    // Menghapus campaign
    public function destroy($id)
    {
        $campaign = Campaign::find($id);

        if (!$campaign) {
            return response()->json(['error' => 'Campaign not found'], 404);
        }

        if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return response()->json(['message' => 'Campaign deleted successfully'], 204);
    }
}
