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
            'image' => 'required|image|mimes:jpg,png,jpg|max:2048',
            'pdf' => 'required|mimes:pdf|max:2048',
            'title' => 'required|min:3',
            'deskripsi' => 'required|min:3',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);



        // upload image
        $image = $request->file('image');
        $image->storeAs('products', $image->hashName(), 'public');

        // upload pdf
        $pdf = $request->file('pdf');
        $pdf->storeAs('pdfs', $pdf->hashName(), 'public');

        // upload pdf
        $validated['image'] = $image->hashName();
        $validated['pdf'] = $pdf->hashName();

        Product::create($validated);

        return redirect()->route('produk.index')->with('sukses', 'Data berhasil ditambahkan');
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
            'image' => 'nullable|image|mimes:jpg,png,jpg|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:2048',
            'title' => 'required|min:3',
            'deskripsi' => 'required|min:3',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $produk = Product::findOrFail($id);

        //update gambar
        if ($request->hasFile('image')) {

            Storage::delete('public/product/' . $produk->image);
            $image = $request->file('image');
            $image->storeAs('products', $image->hashName(), 'public');
            $validated['image'] = $image->hashName();
        }

        // update file
        if ($request->hasFile('pdf')) {

            Storage::delete('public/pdfs/' . $produk->pdf);
            $pdf = $request->file('pdf');
            $pdf->storeAs('pdf', $pdf->hashName(), 'public');
            $validated['pdf'] = $pdf->hashName();
        }

        $produk->update($validated);
        return redirect()->route('produk.index')->with('sukses', 'Data berhasil ditambahkan');
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
