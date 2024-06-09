<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\tambah_keranjang;
use Illuminate\Http\Request;

class tambah_keranjangController extends Controller
{
    public function index(Request $request)
    {
        $response = $this->default_response;

        // get data cart berdasarkan cos id dann checout id null
        $keranjang = tambah_keranjang::where('customer_id', $request->user()->id)
            ->whereNull('checkout_id')
            ->with('product')
            ->get();

        $response['success'] = true;
        $response['data'] = $keranjang;

        return response()->json($response);
    }

    
    public function store(Request $request)
    {

        $response = $this->default_response;

        //validasi
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'jumlah' => 'required|numeric|min:1',
        ]);

        $product = Product::find($request->product_id);
        if ($request->jumlah > $product->stok) {
            $response['success'] = false;
            $response['message'] = 'Stok tidak mencukupi';
            return response()->json($response);
        }

        $tambah_keranjang = tambah_keranjang::where('product_id', $request->product_id)
            ->where('customer_id', $request->user()->id)
            ->whereNull('checkout_id')
            ->first();

        if ($tambah_keranjang) {
            $tambah_keranjang->jumlah += $request->jumlah;
            $tambah_keranjang->harga_satuan = (int)$product->harga;
            $tambah_keranjang->total_harga = $tambah_keranjang->harga_satuan * $tambah_keranjang->jumlah;
            $tambah_keranjang->save();
        } else {
            $tambah_keranjang = new tambah_keranjang();
            $tambah_keranjang->product_id = $request->product_id;
            $tambah_keranjang->customer_id = $request->user()->id;
            $tambah_keranjang->harga_satuan = (int)$product->harga;
            $tambah_keranjang->jumlah = $request->jumlah;
            $tambah_keranjang->total_harga = $tambah_keranjang->harga_satuan * $tambah_keranjang->jumlah;
            $tambah_keranjang->save();
        }

        $response['success'] = true;
        $response['message'] = 'Produk ditambahkan ke keranjang';
        $response['data'] = $tambah_keranjang;
        return response()->json($response);
    }

    public function update(Request $request, string $id)
    {
        //
        $response = $this->default_response;

        //validasi
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
        ]);


        $tambah_keranjang = tambah_keranjang::where('customer_id', $request->user()->id)
            ->whereNull('checkout_id')
            ->with('product')
            ->find($id);

        if (empty($tambah_keranjang)) {
            $response['success'] = false;
            $response['message'] = 'Produk tidak ditemukan';
            return response()->json($response);
        } 

        if($request->jumlah > $tambah_keranjang->product->stok) {
            $response['success'] = false;
            $response['message'] = 'Stok tidak mencukupi';
            return response()->json($response);
        }
    
            $tambah_keranjang->harga_satuan = (int)$tambah_keranjang->product->harga;
            $tambah_keranjang->jumlah = $request->jumlah;
            $tambah_keranjang->total_harga = $tambah_keranjang->harga_satuan * $tambah_keranjang->jumlah;
            $tambah_keranjang->save();
        

        $response['success'] = true;
        $response['message'] = 'keranjang berhasila diupdate';
        $response['data'] = $tambah_keranjang;
        return response()->json($response);

    }

    public function destroy(Request $request, string $id)
    {
        $response = $this->default_response;

        $add_to_cart = tambah_keranjang::where('customer_id', $request->user()->id)
                                ->whereNull('checkout_id')->find($id);

        if (empty($add_to_cart)) {
            $response['success'] = false;
            $response['message'] = 'cart tidak ditemukan';
            return response()->json($response);
        }

        $add_to_cart->delete();
        $response['success'] = true;
        $response['message'] = 'keranjang berhasil di hapus';
        $response['data'] = $add_to_cart;
        return response()->json($response);
    }

}
