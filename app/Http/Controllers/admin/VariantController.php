<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class  VariantController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::where('name', '!=', 'Wallet')->with('items')->orderby('sort')->paginate(15);
        return view('admin.pages.variant.index', compact('products'));
    }

    // Show the create form
    public function create()
    {
        $categories = Categorie::all();
        return view('admin.pages.products.create', compact('categories'));
    }

    // Store the new
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'productID' => 'required|exists:products,id',
                'description' => 'sometimes|nullable|string',
                'denom' => 'sometimes|nullable|string',
            ]);

            Item::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'product_id' => $request->input('productID'),
                'description' => $validated['description'] ?? null,
                'denom' => $validated['denom'] ?? null,
            ]);

            return back()->with('success', 'Variant Added Successfully.');
        } catch (\Exception $exception) {
            return back()->with('success', 'Failed to add variant: ' . $exception->getMessage());
        }
    }


    // Show the edit form
    public function edit($id)
    {
    }

    // Update the product
    public function update(Request $request, $id)
    {
        $product = Item::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'sort' => 'sometimes|nullable|numeric',
            'description' => 'sometimes|nullable|string',
            'denom' => 'sometimes|nullable|string',
        ]);
        // Update fields
        $product->update([
            'name' => $request['name'],
            'price' => $request['price'],
            'sort' => $request->input('sort'),
            'description' => $request['description'] ?? null,
            'denom' => $request['denom'] ?? null,
        ]);

        return back()->with('success', 'Product updated successfully.');
    }

    // Delete the product
    public function destroy($id)
    {
        $product = Item::findOrFail($id);

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function variant($id)
    {
        $variants = Item::where('product_id', $id)->orderby('sort')->get();
        $product = Product::where('id', $id)->first() ?? '';
        return view('admin.pages.variant.variant', compact('variants', 'product'));
    }
}
