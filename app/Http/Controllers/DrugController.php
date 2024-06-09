<?php
namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DrugController extends Controller
{

    public function index()
    {
        $drugs = Drug::all();
        return response()->json($drugs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'Drug_name' => 'required|string|max:255',
            'Effective_Material' => 'required|string|max:255',
            'Side_Effects' => 'required|string|max:255',
            'Other_Information' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $directory = 'public/drug_images';
            // Check if the directory exists, create it if not
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }

            $imagePath = $request->file('image')->store($directory);
            $imageUrl = Storage::url($imagePath);
        }

        $drug = Drug::create([
            'Drug_name' => $request->Drug_name,
            'Effective_Material' => $request->Effective_Material,
            'Side_Effects' => $request->Side_Effects,
            'Other_Information' => $request->Other_Information,
            'image' => $imageUrl,
        ]);

        return response()->json($drug, 201);
    }

    public function show($id)
    {
        $drug = Drug::findOrFail($id);
        return response()->json($drug);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'Drug_name' => 'string|max:255',
        'Effective_Material' => 'string|max:255',
        'Side_Effects' => 'string|max:255',
        'Other_Information' => 'string|max:255',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $drug = Drug::findOrFail($id);
    $drug->update($request->only([
        'Drug_name',
        'Effective_Material',
        'Side_Effects',
        'Other_Information',
    ]));

    if ($request->hasFile('image')) {
        $directory = 'public/drug_images';
        // Check if the directory exists, create it if not
        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }

        // Delete old image
        if ($drug->image) {
            Storage::delete('public/' . str_replace('/storage/', '', $drug->image));
        }

        $imagePath = $request->file('image')->store($directory);
        $imageUrl = Storage::url($imagePath);
        $drug->image = $imageUrl;
    }    
    $drug->save();
    return response()->json($drug);
}



    public function destroy($id)
    {
        $drug = Drug::findOrFail($id);

        // Delete image from storage
        if ($drug->image) {
            Storage::delete('public/' . str_replace('/storage/', '', $drug->image));
        }

        $drug->delete();

        return response()->json(null, 204);
    }
}
