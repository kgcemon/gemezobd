<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SliderImages;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = SliderImages::latest()->get();
        return view('admin.pages.slider.index', compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'nullable|url',
            'images_url' => 'required|image|mimes:jpeg,png,jpg,webp',
        ]);

        // ✅ File store
        $path = $request->file('images_url')->store('sliders', 'public');

        // ✅ Full URL generate
        $fullUrl = url("storage/" . $path);

        // ✅ Save to DB
        SliderImages::create([
            'link' => $request->link,
            'images_url' => $fullUrl,
        ]);

        return back()->with('success', 'Slider added successfully.');
    }

    public function update(Request $request, $id)
    {
        try {
            $slider = SliderImages::findOrFail($id);

            $request->validate([
                'link' => 'nullable|url',
                'images_url' => 'image|mimes:jpeg,png,jpg,webp',
            ]);

            if ($request->hasFile('images_url')) {
                File::delete(public_path('storage/' . $slider->image));
                $slider->images_url = 'storage/'.$request->file('images_url')->store('sliders', 'public');
            }

            $slider->link = $request->link;
            $slider->save();

            return back()->with('success', 'Slider updated successfully.');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        $slider = SliderImages::findOrFail($id);

        File::delete(public_path('storage/' . $slider->image));
        $slider->delete();

        return back()->with('success', 'Slider deleted successfully.');
    }
}
