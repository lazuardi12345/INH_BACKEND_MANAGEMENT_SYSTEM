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
        $programs = DistribusiProgram::all();

        return response()->json([
            'data' => $programs,
            'message' => 'Data distribusi program fetched successfully',
        ], 200);
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
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
    }

    // Menampilkan detail data
    public function show($id)
    {
        $program = DistribusiProgram::find($id);

        if (!$program) {
            return response()->json(['error' => 'Distribusi program not found'], 404);
        }

        return response()->json($program, 200);
    }

    // Memperbarui data
    public function update(Request $request, $id)
    {
        $program = DistribusiProgram::find($id);

        if (!$program) {
            return response()->json(['error' => 'Distribusi program not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'author' => 'required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            if ($program->image && Storage::disk('public')->exists($program->image)) {
                Storage::disk('public')->delete($program->image);
            }
            $imagePath = $request->file('image')->store('distribusi_program_images', 'public');
            $program->image = $imagePath;
        }

        $program->update([
            'title' => $validated['title'],
            'deskripsi' => $validated['deskripsi'],
            'author' => $validated['author'],
        ]);

        return response()->json([
            'message' => 'Distribusi program updated successfully!',
            'program' => $program,
        ], 200);
    }

    // Menghapus data
    public function destroy($id)
    {
        $program = DistribusiProgram::find($id);

        if (!$program) {
            return response()->json(['error' => 'Distribusi program not found'], 404);
        }

        if ($program->image && Storage::disk('public')->exists($program->image)) {
            Storage::disk('public')->delete($program->image);
        }

        $program->delete();

        return response()->json(['message' => 'Distribusi program deleted successfully'], 204);
    }
}
