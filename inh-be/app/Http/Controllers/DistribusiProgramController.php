<?php

namespace App\Http\Controllers;

use App\Models\DistribusiProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DistribusiProgramController extends Controller
{
    // Menampilkan semua data Distribusi Program
    public function index()
    {
        try {
            $programs = DistribusiProgram::all();

            return response()->json([
                'data' => $programs,
                'message' => 'Data distribusi program fetched successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch distribusi program data',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'author' => 'required|string|max:255',
            ]);

            // Proses file gambar
            $imagePath = $request->hasFile('image') 
                ? $request->file('image')->store('distribusi_program_images', 'public') 
                : null;

            // Simpan data ke database
            $program = DistribusiProgram::create([
                'title' => $validated['title'],
                'deskripsi' => $validated['deskripsi'],
                'image' => $imagePath,
                'author' => $validated['author'],
            ]);

            return response()->json([
                'message' => 'Distribusi program created successfully!',
                'program' => $program,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create distribusi program',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Menampilkan detail data
    public function show($id)
    {
        try {
            $program = DistribusiProgram::find($id);

            if (!$program) {
                return response()->json(['error' => 'Distribusi program not found'], 404);
            }

            return response()->json($program, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch distribusi program detail',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

   // Memperbarui data
   public function update(Request $request, $id)
   {
       try {
           // Cari program berdasarkan ID
           $program = DistribusiProgram::find($id);
   
           if (!$program) {
               return response()->json(['error' => 'Distribusi program not found'], 404);
           }
   
           // Validasi input
           $validated = $request->validate([
               'title' => 'nullable|string|max:255',
               'deskripsi' => 'nullable|string',
               'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
               'author' => 'nullable|string|max:255',
           ]);
   
           // Periksa apakah ada perubahan pada 'title', 'author', atau 'deskripsi'
           $dataToUpdate = [];
   
           if ($request->has('title')) {
               $dataToUpdate['title'] = $validated['title'];
           }
   
           if ($request->has('deskripsi')) {
               $dataToUpdate['deskripsi'] = $validated['deskripsi'];
           }
   
           if ($request->has('author')) {
               $dataToUpdate['author'] = $validated['author'];
           }
   
           // Proses file gambar jika ada
           if ($request->hasFile('image')) {
               // Hapus gambar lama jika ada
               if ($program->image && Storage::disk('public')->exists($program->image)) {
                   Storage::disk('public')->delete($program->image);
               }
               // Simpan gambar baru
               $imagePath = $request->file('image')->store('distribusi_program_images', 'public');
               $dataToUpdate['image'] = $imagePath;
           }
   
           // Update data jika ada perubahan
           if (count($dataToUpdate) > 0) {
               $program->update($dataToUpdate);
   
               return response()->json([
                   'message' => 'Distribusi program updated successfully!',
                   'program' => $program,
               ], 200);
           } else {
               return response()->json([
                   'message' => 'No changes made to the distribusi program.',
               ], 200);
           }
   
       } catch (\Exception $e) {
           return response()->json([
               'error' => 'Failed to update distribusi program',
               'message' => $e->getMessage(),
           ], 500);
       }
   }
   


    // Menghapus data
    public function destroy($id)
    {
        try {
            $program = DistribusiProgram::find($id);

            if (!$program) {
                return response()->json(['error' => 'Distribusi program not found'], 404);
            }

            // Hapus gambar jika ada
            if ($program->image && Storage::disk('public')->exists($program->image)) {
                Storage::disk('public')->delete($program->image);
            }

            // Hapus data program
            $program->delete();

            return response()->json(['message' => 'Distribusi program deleted successfully'], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete distribusi program',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
