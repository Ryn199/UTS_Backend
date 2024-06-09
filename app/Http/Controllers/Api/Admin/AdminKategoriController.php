<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class AdminKategoriController extends Controller
{

    public function index()
    {
        $response = $this->default_response;

        try {
            $kategori = Category::all();
            
            $response['success'] = true;
            $response['message'] = 'Berhasil mengambil data kategori';
            $response['data'] = $kategori;
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }


    public function store(AddKategoriRequest $request)
    {
        $response = $this->default_response;

        try{
            $data = $request->validated();

            $kategori = new Category();
            $kategori->nama = $data['nama'];
            $kategori->deskripsi = $data['deskripsi'];
            $kategori->save();

            $response['success'] = true;
            $response['data'] = [   
                'kategori' => $kategori
            ];
            $response['message'] = 'kategori created successfully';
        }catch(Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    
    }

    public function show(string $id)
    {
        $response = $this->default_response;

        try{
            $kategori = Category::find($id);

            $response['success'] = true;
            $response['message'] = 'Get kategori success';
            $response['data'] = [
                'kategori' => $kategori
            ];  
        }catch(Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }



    public function update(UpdateKategoriRequest $request, String $id)
    {
        $response = $this->default_response;

        try{
            $data = $request->all();

            $kategori = Category::find($id);
            $kategori->nama = $data['nama'];
            $kategori->deskripsi = $data['deskripsi'];
            $kategori->save();

            $response['success'] = true;
            $response['data'] = [
                'kategori' => $kategori,
            ];

            $response['message'] = 'kategori Updated successfully';
        }catch(Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    
    }

    public function destroy(String $id)
    {
        $response = $this->default_response;
        
        try {
            $kategori = Category::find($id);
            
            if ($kategori) {
                $kategori->delete();
    
                $response['success'] = true;
                $response['message'] = 'kategori berhasil dihapus';
            } else {
                $response['success'] = false;
                $response['message'] = 'kategori tidak ditemukan';
            }
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
    
        return response()->json($response);
    }
}
