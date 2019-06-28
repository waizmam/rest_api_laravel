<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\API\ApiError;

class ProductController extends Controller
{
    private $product;
    public function __construct(Product $product){
        $this->product = $product;
    }

    public function index(){
        //return Product::all();
        $data = ['data' => $this->product->paginate(10)];
        return response()->json($data);
        //return $this->product->all();
    }

    /*public function show(Product $id){
        $data = ['data' => $id];
        return response()->json($data);
        //return $id;
    }*/

    //OU Assim

    public function show($id){
        $product = $this->product->find($id);
        if(!$product) return response()->json(ApiError::errorMessage('Produto não encontrado', 4040), 404);
        $data = ['data' => $product];
        return response()->json($data);
        //return $id;
    }

    public function store(Request $request){
       try{
            $productData = $request->all();
            $this->product->create($productData);
            $return = ['data' =>['msg'=> 'Produto criado com sucesso!']];
            return response()->json($return,201);
       }catch(\Exception $e){
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realziar a operação de Insert!', 1010), 500);
       }
    }

    public function update(Request $request, $id){
        try{
             $productData = $request->all();
             $product = $this->product->find($id);
             $product->update($productData);
             $return = ['data' =>['msg'=> 'Produto atualizado com sucesso!']];
             return response()->json($return,201);
        }catch(\Exception $e){
             if (config('app.debug')) {
                 return response()->json(ApiError::errorMessage($e->getMessage(), 1011), 500);
             }
             return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de Update!', 1011), 500);
        }
    }

    public function delete(Product $id){
        try {
            $id->delete();
            $return = ['data' =>['msg'=> 'Produto '.$id->name.' removido com sucesso!']];
            return response()->json($return,200);
        }catch(\Exception $e){
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de Delete!', 1012), 500);
       }
    }
}
