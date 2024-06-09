<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProdukRequest;
use App\Http\Requests\UpdateProdukRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $response = $this->default_response;

        try {
            $produk = Product::with('category')->get();

            $response['success'] = true;
            $response['data'] = [
                'produk' => $produk
            ];
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }



    public function store(AddProdukRequest $request)
    {
        $response = $this->default_response;

        try {
            $data = $request->validated();

            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->storeAs('Data-Images', $file->hashName(), 'public');
            }

            $produk = new Product();
            $produk->nama = $data['nama'];
            $produk->deskripsi = $data['deskripsi'];
            $produk->stok = $data['stok'];
            $produk->harga = $data['harga'];
            $produk->image = $path ?? null;
            $produk->category_id = $data['category_id'];
            $produk->save();

            DB::commit();


            $response['success'] = true;
            $response['data'] = [
                'produk' => $produk->with('category')->find($produk->id),
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }


        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $response = $this->default_response;

        try {
            $produk = Product::with('category')->find($id);

            $response['success'] = true;
            $response['data'] = [
                'produk' => $produk
            ];
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }


        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdukRequest $request, String $id)
    {
        $response = $this->default_response;

        try {
            $data = $request->validated();
            DB::beginTransaction();


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $path = $file->storeAs('Data-Images', $file->hashName(), 'public');
            }

            $produk = Product::find($id);

            if ($produk) {
                $produk->nama = $data['nama'];
                $produk->deskripsi = $data['deskripsi'];
                $produk->stok = $data['stok'];
                $produk->harga = $data['harga'];

                if ($request->hasFile('image')) {
                    $produk->image = $path ?? null;
                }
                $produk->category_id = $data['category_id'];
                $produk->save();

                DB::commit();


                $response['success'] = true;
                $response['data'] = [
                    'produk' => $produk->with('category')->find($produk->id),
                ];
                $response['message'] = 'produk updated successfully';
            } else {
                DB::rollBack();

                $response['success'] = false;
                $response['message'] = 'produk not found';
            }
        } catch (Exception $e) {
            DB::rollBack();

            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }


    public function destroy(String $id)
    {
        $response = $this->default_response;

        try {
            $produk = Product::find($id);

            if ($produk) {
                if ($produk->image && Storage::disk('public')->exists($produk->image)) {
                    Storage::disk('public')->delete($produk->image);
                }
                $produk->delete();

                $response['success'] = true;
                $response['message'] = 'produk berhasil di hapus';
            } else {
                $response['success'] = false;
                $response['message'] = 'produk tidak ditemukan';
            }
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }


    // public function destroy(String $id)
    // {
    //     $produk = produk::find($id);


    //     // Hapus gambar dari server
    //     if ($produk->image && Storage::disk('public')->exists($produk->image)) {
    //         Storage::disk('public')->delete($produk->image);
    //     }

    //     $produk->delete();
    // }
}
