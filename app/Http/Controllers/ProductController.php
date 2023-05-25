<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function lists()
    {
        $products = Products::all();
        $categories = Products::select('category')->orderBy('category', 'asc')->groupBy('category')->get();
        $return_data = [
            'products' => $products,
            'categories' => json_encode($categories)
        ];
        return json_encode($return_data);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Products/Products');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $attachments = [];
        $fileName = time().'.jpg';  
        // $request->file->move(public_path('uploads'), $fileName);
        foreach($request->file('file') as  $file)
        {
            $file_extension = strtolower($file->getClientOriginalExtension());
            $filename = rand(12, 34353).time().'_attachment.'.$file_extension;
            $file->storeAs('public/products/images', $filename);
            $attachments[] = $filename;
        }
        Products::create([
            'name' => $request->pname,
            'category' => $request->pcategory,
            'description' => $request->pdescription,
            'datetime' => $request->pdatetime,
            'images' => json_encode($attachments),
        ]);
        // dump($request->all());
        return redirect()->route('products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $products)
    {
        $product = Products::find($request->product_id);
        $product->name = $request->product_name;
        $product->category = $request->product_category;
        $product->description = $request->product_description;
        $product->datetime = $request->product_datetime;
        $product->save();
        return redirect()->route('products');
        // return $request->all();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $products, Request $request)
    {
        $product = Products::find($request->product_id);
        $product->delete();

        return redirect()->route('products');
    }
}
