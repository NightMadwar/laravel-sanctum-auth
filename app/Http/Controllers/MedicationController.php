<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Drug;
use App\Models\ConsumedMedication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicationController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'Drug_name' => 'required|string|max:255',
            'Effective_Material' => 'required|string|max:255',
            'Side_Effects' => 'required|string|max:255',
            'Other_Information' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Doctor_Name' => 'required|string|max:255',
            'User_ID' => 'required|exists:users,id',
            'Date_Prescibed' => 'required|date',
            'period' => 'required|in:morning,afternoon,evening',
        ]);

        // Check if the directory exists, if not, create it
        $directory = 'drug_images';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Store the image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store($directory, 'public');
        }
        

        // Create the drug entry
        $drug = Drug::create([
            'Drug_name' => $request->Drug_name,
            'Effective_Material' => $request->Effective_Material,
            'Side_Effects' => $request->Side_Effects,
            'Other_Information' => $request->Other_Information,
            'image' => $imagePath ?? null,
        ]);

        // Create the consumed medication entry
        $consumedMedication = ConsumedMedication::create([
            'Drug_ID' => $drug->id,
            'User_ID' => Auth::user()->id,
            'Doctor_Name' => $request->Doctor_Name,
            'Date_Prescibed' => $request->Date_Prescibed,
            'period' => $request->period,
        ]);

        // Return a JSON response
        return response()->json(['drug' => $drug, 'consumed_medication' => $consumedMedication], 201);
    }
}
