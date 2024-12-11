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
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'author' => 'required|string|max:255',
            ]);

            $imagePath = $request->hasFile('image') 
                ? $request->file('image')->store('distribusi_program_images', 'public') 
                : null;

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

            return response()->json([
                'data' => $program,
                'message' => 'Distribusi program fetched successfully',
            ], 200);
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
            $program = DistribusiProgram::find($id);

            if (!$program) {
                return response()->json(['error' => 'Distribusi program not found'], 404);
            }

            $validated = $request->validate([
                'title' => 'nullable|string|max:255',
                'deskripsi' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'author' => 'nullable|string|max:255',
            ]);

            // Hanya perbarui field yang dikirimkan
            if ($request->has('title')) {
                $program->title = $validated['title'];
            }

            if ($request->has('deskripsi')) {
                $program->deskripsi = $validated['deskripsi'];
            }

            if ($request->has('author')) {
                $program->author = $validated['author'];
            }

            // Perbarui gambar jika ada
            if ($request->hasFile('image')) {
                if ($program->image && Storage::disk('public')->exists($program->image)) {
                    Storage::disk('public')->delete($program->image);
                }

                $program->image = $request->file('image')->store('distribusi_program_images', 'public');
            }

            $program->save();

            return response()->json([
                'message' => 'Distribusi program updated successfully!',
                'program' => $program,
            ], 200);
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

            if ($program->image && Storage::disk('public')->exists($program->image)) {
                Storage::disk('public')->delete($program->image);
            }

            $program->delete();

            return response()->json([
                'message' => 'Distribusi program deleted successfully',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete distribusi program',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
