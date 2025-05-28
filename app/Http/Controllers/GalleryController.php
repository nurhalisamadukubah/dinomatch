<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index($id)
    {
        $player = User::find($id);
        $level = $player->level;
        $galleries = Gallery::take($level)->get();
        return view('galleries.index', compact('galleries'));
    }

    public function showAll()
    {
        $galleries = Gallery::all();
        return view('galleries.index', compact('galleries'));
    }

    /**
     * Menampilkan form untuk membuat gallery baru.
     */
    public function create()
    {
        return view('galleries.create');
    }

    /**
     * Menyimpan gallery baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'dificulty' => 'required|in:easy,medium,hard',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Batasi jenis dan ukuran file
        ]);

        // Simpan gambar ke storage
        $imagePath = $request->file('image')->store('galleries', 'public');

        // Simpan data ke database
        Gallery::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'dificulty' => $request->dificulty,
        ]);

        return redirect()->route('galleries.show')->with('success', 'Gallery created successfully.');
    }

    /**
     * Menampilkan detail gallery.
     */
    public function show($id)
    {
        $gallery = Gallery::find($id);
        return view('galleries.detail', compact('gallery'));
    }

    /**
     * Menampilkan form untuk mengedit gallery.
     */
    public function edit(Gallery $gallery)
    {
        return view('galleries.edit', compact('gallery'));
    }

    /**
     * Mengupdate gallery di database.
     */
    public function update(Request $request, Gallery $gallery)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096', // Gambar opsional
        ]);

        // Jika ada file gambar baru, simpan dan hapus gambar lama
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            Storage::disk('public')->delete($gallery->image);

            // Simpan gambar baru
            $imagePath = $request->file('image')->store('galleries', 'public');
            $gallery->image = $imagePath;
        }

        // Update data
        $gallery->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $gallery->image, // Gunakan path gambar yang sudah diupdate
        ]);

        return redirect()->route('galleries.show')->with('success', 'Gallery updated successfully.');
    }

    /**
     * Menghapus gallery dari database.
     */
    public function destroy(Gallery $gallery)
    {
        // Hapus gambar dari storage
        Storage::disk('public')->delete($gallery->image);

        // Hapus data dari database
        $gallery->delete();

        return redirect()->route('galleries.show')->with('success', 'Gallery deleted successfully.');
    }
}
