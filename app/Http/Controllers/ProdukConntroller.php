<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use Illuminate\View\view;
use Illuminate\View\View;

class ProdukConntroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $produk = Product::all();
        return view('product.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'pdf' => 'required|mimes:pdf|max:2048',
            'title' => 'required|min:3',
            'deskripsi' => 'required|min:3',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        // SIMPAN IMAGE
        $imagePath = $request->file('image')->store('products', 'public');

        // SIMPAN PDF
        $pdfPath = $request->file('pdf')->store('pdfs', 'public');

        $validated['image'] = $imagePath;
        $validated['pdf'] = $pdfPath;

        Product::create($validated);

        return redirect()->route('produk.index')
            ->with('sukses', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Product::findOrFail($id);
        return view('product.show', compact('produk'));
    }



    public function edit(string $id)
    {
        $produk = Product::findOrFail($id);
        return view('product.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:2048',
            'title' => 'required|min:3',
            'deskripsi' => 'required|min:3',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $produk = Product::findOrFail($id);


        // UPDATE IMAGE

        if ($request->hasFile('image')) {

            // hapus file lama
            if ($produk->image && Storage::disk('public')->exists($produk->image)) {
                Storage::disk('public')->delete($produk->image);
            }

            // simpan baru
            $validated['image'] = $request->file('image')->store('products', 'public');
        }


        // UPDATE PDF

        if ($request->hasFile('pdf')) {

            // hapus file lama
            if ($produk->pdf && Storage::disk('public')->exists($produk->pdf)) {
                Storage::disk('public')->delete($produk->pdf);
            }

            // simpan baru
            $validated['pdf'] = $request->file('pdf')->store('pdfs', 'public');
        }

        $produk->update($validated);

        return redirect()->route('produk.index')
            ->with('sukses', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Product::findOrFail($id);

        // hapus gambar
        if ($produk->image) {
            Storage::disk('public')->delete('products/' . $produk->image);
        }

        // hapus pdf
        if ($produk->pdf) {
            Storage::disk('public')->delete('pdfs/' . $produk->pdf);
        }

        // hapus database
        $produk->delete();

        return redirect()->route('produk.index')
            ->with('sukses', 'Data berhasil dihapus');
    }
}
