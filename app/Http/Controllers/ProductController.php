<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 'success',
            'data' => [
                'products' => $products
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'price' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'fail',
                'data' => $validator->errors()
            ], 400);
        }

        $product = auth()->user()->products()->create($request->all());
        return  response()->json([
            'status' => 'success',
            'data' => [
                'product' => $product
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     */
    public function show($id)
    {
        $product = Product::find($id);
        if ($product){
            return response()->json([
                'status' => 'success',
                'data' => [
                    'product' => $product
                ]
            ], 200);
        }else{
            return response()->json([
                'status' => 'fail',
                'data' => [
                    'id' => 'Product not found'
                ]
            ],404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $product = auth()->user()->products()->find($id);
        if ($product){
            $product->update($request->all());
            return response()->json([
                'status' => 'success',
                'data' => [
                    'product' => $product
                ]
            ], 200);
        }else{
            return response()->json([
                'status' => 'fail',
                'data' => [
                    'id' => 'Product not found'
                ]
            ],404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        if (Product::destroy($id)){
            return response()->json([
                'status' => 'success',
                'data' => null
            ], 200);
        }else{
            return response()->json([
                'status' => 'fail',
                'data' => [
                    'id' => 'Product not found'
                ]
            ],404);
        }
    }

    public function search($name){
        $products = Product::where('name', 'like', '%'.$name.'%')->get();
        return response()->json([
            'status' => 'success',
            'data' => [
                'products' => $products
            ]
        ], 200);
    }

}
