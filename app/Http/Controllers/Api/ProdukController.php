<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProdukController extends Controller
{

    public function index()
    {
        $response = $this->default_response;

        try {
            $produk = Product::all();
            
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

}
