<?php

namespace App\Http\Controllers;

use App\{Product, Provider};
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('view', Product::class);

        $products = Product::paginate();

        return view('product/index')->with(['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Product::class);

        $product = new Product;
        $providers = Provider::select('id', 'name')->get();

        return view('product/create')->with([
            'product' => $product,
            'providers' => $providers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $this->authorize('create', Product::class);

        request()->validate([
            'name' => 'required|unique:products',
            'provider_id' => 'required',
            'stock' => 'required|numeric',
            'min_stock' => 'nullable|numeric',
            'price' => 'required|numeric',
            'description' => 'required'
        ]);

        DB::transaction(function () {
            $product = Product::create([
                'name' => request()->name,
                'provider_id' => request()->provider_id,
                'stock' => request()->stock,
                'min_stock' => request()->min_stock,
                'price' => request()->price,
                'description' => request()->description,
            ]);

            $product->purchases()->create([
                'description' => "{$product->name}: Inventario inicial",
                'stock' => $product->stock,
                'price' => $product->price,
            ]);
        });

        return back()->with(['flash_success' => "Producto ".request()->name." creado exitosamente"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $providers = Provider::select('id', 'name')->get();

        return view('product.edit')->with([
            'product' => $product,
            'providers' => $providers
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Product $product)
    {
        $this->authorize('update', $product);

        request()->validate([
            'name' => 'required|unique:products,name'. $product->id,
            'provider_id' => 'required',
            'stock' => 'required|numeric',
            'min_stock' => 'nullable|numeric',
            'price' => 'required|numeric',
            'description' => 'required'
        ]);

        $product->update([
            'name' => request()->name,
            'provider_id' => request()->provider_id,
            'stock' => request()->stock,
            'min_stock' => request()->min_stock,
            'price' => request()->price,
            'description' => request()->description,
        ]);

        return back()->with(['flash_success' => "Producto {$product->name} actualizado exitosamente"]);
    }
}
