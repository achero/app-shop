<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\ProductImage;
use App\Category;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products')); // listado

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories')); // Formulario de registro
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validar
        $messages = [
            'name.required' => 'Es necesario ingresar un nombre para el producto.',
            'name.min' => 'El nombre del producto debe tener al menos 3 caracteres.',
            'description.required' => 'La descripción corta debe ser obligatorio.',
            'description.max' => 'La descripción corta solo admite hasta 200 caracteres.',
            'price.required' => 'Es obligatorio definir un precio para el producto.',
            'price.numeric' => 'Ingrese un precio válido.',
            'price.min' => 'No se admiten valores negativos.',
        ];
        $rules = [
            'name' => 'required|min:3',
            'description' => 'required|max:200',
            'price' => 'required|numeric|min:0',
        ];
        $this->validate($request, $rules, $messages);

        //Registrar el nuevo producto en la bd
        
        //dd(substr($request->category_id, 0, 1));
        $product = new Product();
        $product ->name = $request->input('name');
        $product ->description = $request->input('description');
        $product ->price = $request->input('price');
        $product ->long_description = $request->input('long_description');
        if(substr($request->category_id, 0, 1)==="0"){
            $product->category_id = null;
        }else{
            $product->category_id = $request->category_id; 
        }
        $product->save(); // INSERT
        return redirect('/admin/products');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::orderBy('name')->get();
        $product = Product::find($id);
        return view('admin.products.edit',compact('product','categories')); //formulario de edicion
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
          //validar
        $messages = [
            'name.required' => 'Es necesario ingresar un nombre para el producto.',
            'name.min' => 'El nombre del producto debe tener al menos 3 caracteres.',
            'description.required' => 'La descripción corta debe ser obligatorio.',
            'description.max' => 'La descripción corta solo admite hasta 200 caracteres.',
            'price.required' => 'Es obligatorio definir un precio para el producto.',
            'price.numeric' => 'Ingrese un precio válido.',
            'price.min' => 'No se admiten valores negativos.',
        ];
        $rules = [
            'name' => 'required|min:3',
            'description' => 'required|max:200',
            'price' => 'required|numeric|min:0',
        ];
        $this->validate($request, $rules, $messages);
        //Alctualizar producto
        $product = Product::find($id);
        $product ->name = $request->input('name');
        $product ->description = $request->input('description');
        $product ->price = $request->input('price');
        $product ->long_description = $request->input('long_description');
        if(substr($request->category_id, 0, 1)==="0"){
            $product->category_id = null;
        }else{
            $product->category_id = $request->category_id; 
        }
        $product->save(); // UPDATE

        return redirect('/admin/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProductImage::where('product_id', $id)->delete();
        $product = Product::find($id);
        $product->delete(); // DELETE

        return back();
    }
}
