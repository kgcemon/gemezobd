<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Code;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;

class CodesController extends Controller
{
    // Show all products
    public function index()
    {
        $products = Product::where('name', '!=', 'Wallet')->with('items')->orderby('sort')->paginate(10);
        return view('admin.pages.codes.index', compact('products'));
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
        $validatedData = $request->validate([
            'codes' => 'required|string',
            'product_id' => 'required',
            'item_id' => 'required',
        ]);

        try {
            $lines = preg_split('/\r\n|\r|\n/', $validatedData['codes']);
            $item = Item::where('product_id', $validatedData['product_id'])->where('id', $request->input('item_id'))->first();

            foreach ($lines as $line) {
                $code = trim($line);

                if (!empty($code)) {
                    Code::create([
                        'product_id' => $request->input('product_id'),
                        'item_id' => $request->input('item_id'),
                        'code' => $code,
                        'denom' => $item->denom ?? null,
                    ]);
                }
            }
        }catch (\Exception $exception){
            return $exception->getMessage();
        }

        return back()->with('success', 'Codes added successfully.');
    }

    public function show($id)
    {
        $codesCountPerVariant = Code::selectRaw("denom,
        SUM(CASE WHEN status = 'unused' THEN 1 ELSE 0 END) as total_unused,
        SUM(CASE WHEN status = 'used' THEN 1 ELSE 0 END) as total_used")->groupBy('denom')
            ->with('variant')->where('product_id', $id)->get();

        $product = Product::where('id', $id)->first() ?? '';
        return view('admin.pages.codes.codes', compact('product','codesCountPerVariant'));
    }


    // Show the edit form
    public function singleCode(Request $request, $denom)
    {
        $query = Code::where('denom', $denom);

        // Search by code
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $codes = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();

        $products = Product::orderBy('sort')->get();

        return view('admin.pages.codes.edit', compact('codes', 'products'));
    }


    public function edit($id)
    {

    }

    // Update the product
    public function update(Request $request, $id)
    {
        $code = Code::findOrFail($id);

        $request->validate([
            'item_id' => 'required|numeric',
            'code' => 'sometimes|nullable|string',
        ]);
        // Update fields
        $code->update([
            'code' => $request->input('code'),
            'item_id' => $request->input('item_id'),
            'status' => $request->input('status'),
            'denom' => $request->input('denom'),
        ]);

        return back()->with('success', 'Product updated successfully.');
    }

    // Delete the product
    public function destroy($id)
    {
        $product = Code::findOrFail($id);

        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

}
