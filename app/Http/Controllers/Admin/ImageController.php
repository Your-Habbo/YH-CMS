<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(Request $request)
{
    $query = Image::query();        

    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('admin.dashboard')],
        ['label' => 'Images'],

    ];

    // Apply search filter if provided
    if ($request->has('search')) {
        $query->where('filename', 'like', '%' . $request->search . '%');
    }

    // Paginate the images
    $images = $query->paginate(12); // Adjust the number as needed

    return view('admin.images.index', compact('images', 'breadcrumbs'));
}

    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = time().'_'.$file->getClientOriginalName();
                $filepath = $file->storeAs('uploads', $filename, 'public');
    
                Image::create([
                    'filename' => $filename,
                    'filepath' => $filepath,
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Images uploaded successfully.');
    }
    

    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        Storage::disk('public')->delete($image->filepath);
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
}
