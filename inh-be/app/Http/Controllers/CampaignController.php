<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    // Mengambil semua data campaign
    public function index()
    {
        try {
            $campaigns = Campaign::all();

            return response()->json([
                'data' => $campaigns,
                'message' => 'Campaigns retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve campaigns',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menyimpan campaign baru
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
                'kategori' => 'required|string|in:sedekah umum,palestina,nasional,internasional',
            ]);

            // Simpan file gambar ke penyimpanan publik
            $imagePath = $request->file('image')->store('campaign_images', 'public');

            // Simpan data ke database
            $campaign = Campaign::create([
                'title' => $validated['title'],
                'deskripsi' => $validated['deskripsi'],
                'image' => $imagePath,
                'kategori' => $validated['kategori'],
            ]);

            return response()->json([
                'message' => 'Campaign created successfully!',
                'campaign' => $campaign,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create campaign',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan detail campaign
    public function show($id)
    {
        try {
            $campaign = Campaign::find($id);

            if (!$campaign) {
                return response()->json(['error' => 'Campaign not found'], 404);
            }

            return response()->json([
                'data' => $campaign,
                'message' => 'Campaign retrieved successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to retrieve campaign',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Memperbarui campaign
    public function update(Request $request, $id)
    {
        try {
            $campaign = Campaign::find($id);
    
            if (!$campaign) {
                return response()->json(['error' => 'Campaign not found'], 404);
            }
    
            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'kategori' => 'nullable|string|in:sedekah umum,palestina,nasional,internasional',
            ]);
    
            // Jika ada file gambar baru
            if ($request->hasFile('image')) {
                if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
                    Storage::disk('public')->delete($campaign->image);
                }
    
                $imagePath = $request->file('image')->store('campaign_images', 'public');
                $campaign->image = $imagePath;
            }
    
            // Perbarui data lainnya
            $campaign->update(array_filter([
                'title' => $validated['title'] ?? $campaign->title,
                'deskripsi' => $validated['deskripsi'] ?? $campaign->deskripsi,
                'kategori' => $validated['kategori'] ?? $campaign->kategori,
            ]));
    
            return response()->json([
                'message' => 'Campaign updated successfully!',
                'campaign' => $campaign,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update campaign',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    

    // Menghapus campaign
    public function destroy($id)
    {
        try {
            $campaign = Campaign::find($id);

            if (!$campaign) {
                return response()->json(['error' => 'Campaign not found'], 404);
            }

            if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
                Storage::disk('public')->delete($campaign->image);
            }

            $campaign->delete();

            return response()->json([
                'message' => 'Campaign deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete campaign',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
