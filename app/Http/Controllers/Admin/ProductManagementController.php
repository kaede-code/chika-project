<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;

class ProductManagementController extends Controller
{
    public function index()
    {
        $products = Product::query()->latest()->paginate(5);
        return view('page.admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('page.admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gambar' => ['nullable', 'image', 'max:10240'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:Jus,Salad'],
            'harga' => ['required', 'numeric', 'min:0.01'],
        ], [
            'gambar.uploaded' => 'Gambar gagal diupload. Ukuran file terlalu besar atau file rusak.',
            'gambar.mimes' => 'Gambar harus berformat: jpg, jpeg, png, webp, gif, bmp.',
            'gambar.max' => 'Ukuran gambar maksimal 10MB.',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['gambar' => 'File gagal diupload: ' . $file->getErrorMessage()])->withInput();
            }
            $gambarPath = $file->store('products', 'public');
        }

        Product::create([
            'nama_produk' => $validated['nama_produk'],
            'kategori' => $validated['kategori'],
            'harga' => (int) ceil((float) $validated['harga']),
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('page.admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'gambar' => ['nullable', 'image', 'max:10240'],
            'nama_produk' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:Jus,Salad'],
            'harga' => ['required', 'numeric', 'min:0.01'],
        ], [
            'gambar.uploaded' => 'Gambar gagal diupload. Ukuran file terlalu besar atau file rusak.',
            'gambar.max' => 'Ukuran gambar maksimal 10MB.',
        ]);

        $gambarPath = $product->gambar;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            if (!$file->isValid()) {
                return redirect()->back()->withErrors(['gambar' => 'File gagal diupload: ' . $file->getErrorMessage()])->withInput();
            }
            if ($product->gambar) {
                Storage::disk('public')->delete($product->gambar);
            }
            $gambarPath = $file->store('products', 'public');
        }

        $product->update([
            'nama_produk' => $validated['nama_produk'],
            'kategori' => $validated['kategori'],
            'harga' => (int) ceil((float) $validated['harga']),
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $product = Product::query()->findOrFail($id);

        if ($product->gambar) {
            Storage::disk('public')->delete($product->gambar);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}

