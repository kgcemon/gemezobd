<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::with('category')->orderby('sort')->paginate(15);
        return view('admin.pages.products.index', compact('products'));
    }

    // Show the create form
    public function create()
    {
        $categories = Categorie::all();
        return view('admin.pages.products.create', compact('categories'));
    }

    // Store the new product
    public function store(Request $request)
    {
        try {
            $request->validate([
                'category_id'       => 'required|exists:categories,id',
                'name'              => 'required|string|max:255',
                'slug'              => 'nullable|string|unique:products,slug',
                'description'       => 'nullable|string',
                'short_description' => 'nullable|string|max:200',
                'tags'              => 'nullable|string',
                'keywords'          => 'nullable|string',
                'input_name'        => 'required|string|max:255',
                'input_others'      => 'nullable|string|max:255',
                'total_input'       => 'required|integer|min:1',
                'image'             => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'cover_image'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'sort'              => 'nullable|integer',
                'stock'             => 'required|boolean',
            ]);

            $imagePath = $request->file('image')->store('products', 'public');
            $coverPath = $request->file('cover_image')->store('products', 'public');

            Product::create([
                'category_id'       => $request->category_id,
                'name'              => $request->name,
                'slug'              => $request->slug ?: Str::slug($request->name),
                'description'       => $request->description,
                'short_description' => $request->short_description,
                'tags'              => $request->tags,
                'keywords'          => $request->keywords,
                'input_name'        => $request->input_name,
                'input_others'      => $request->input_others,
                'total_input'       => $request->total_input,
                'image'             => 'storage/' . $imagePath,
                'cover_image'       => 'storage/' . $coverPath,
                'sort'              => $request->sort ?? 0,
                'stock'             => $request->stock,
                'seo_title'         => $request->seo_title,
                'seo_description'   => $request->seo_description,
                'seo_keywords'     =>  $request->seo_keywords
            ]);

            return redirect()->route('admin.products.index')->with('success', 'Product Added Successfully.');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage())->withInput();
        }
    }

    // Show the edit form
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Categorie::all();
        return view('admin.pages.products.edit', compact('product', 'categories'));
    }

    // Update the product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|unique:products,slug,' . $product->id,
            'description'       => 'required|string',
            'short_description' => 'nullable|string|max:200',
            'tags'              => 'nullable|string',
            'keywords'          => 'nullable|string',
            'input_name'        => 'required|string|max:255',
            'input_others'      => 'nullable|string|max:255',
            'total_input'       => 'required|integer|min:1',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover_image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'sort'              => 'nullable|integer',
            'stock'             => 'required|boolean',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            if (File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = 'storage/' . $imagePath;
        }

        if ($request->hasFile('cover_image')) {
            if (File::exists(public_path($product->cover_image))) {
                File::delete(public_path($product->cover_image));
            }
            $coverPath = $request->file('cover_image')->store('products', 'public');
            $product->cover_image = 'storage/' . $coverPath;
        }

        // Update fields
        $product->update([
            'category_id'       => $request->category_id,
            'name'              => $request->name,
            'slug'              => $request->slug ?: Str::slug($request->name),
            'description'       => $request->description,
            'short_description' => $request->short_description,
            'tags'              => $request->tags,
            'keywords'          => $request->keywords,
            'input_name'        => $request->input_name,
            'input_others'      => $request->input_others,
            'total_input'       => $request->total_input,
            'sort'              => $request->sort ?? 0,
            'stock'             => $request->stock,
            'support_country'   => $request->support_country,
            'delivery_system'   => $request->delivery_system,
            'seo_title'         => $request->seo_title,
            'seo_description'   => $request->seo_description,
            'seo_keywords'     =>  $request->seo_keywords
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    // Delete the product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        if (File::exists(public_path($product->cover_image))) {
            File::delete(public_path($product->cover_image));
        }

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
