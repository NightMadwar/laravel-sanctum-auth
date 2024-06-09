<?php
namespace App\Http\Controllers;

use App\Models\ConsumedMedication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsumedMedicationController extends Controller
{

    public function index()
    {
        $userId = Auth::id();
        $consumedMedications = ConsumedMedication::where('User_ID', $userId)->with(['drug', 'user'])->get();
        return response()->json($consumedMedications);
    }

    public function store(Request $request)
{
    $request->validate([
        'Drug_ID' => 'required|exists:drugs,id',
        'Doctor_Name' => 'required|string|max:255',
        'Date_Prescribed' => 'nullable|string',
        'period' => 'required|in:morning,afternoon,evening',        
    ]);

    $userId = Auth::id();

    $data = [
        'Drug_ID' => $request->Drug_ID,
        'User_ID' => $userId,
        'Doctor_Name' => $request->Doctor_Name,
        'period' => $request->period,
    ];

    // Check if Date_Prescribed is provided and not null
    if ($request->has('Date_Prescribed') && !is_null($request->Date_Prescribed)) {
        $data['Date_Prescribed'] = $request->Date_Prescribed;
    }

  

    $consumedMedication = ConsumedMedication::create($data);

    return response()->json($consumedMedication, 201);
}
public function searchByDrugName(Request $request)
{
    $request->validate([
        'drug_name' => 'required|string',
    ]);

    $drugName = $request->input('drug_name');

    $medications = ConsumedMedication::with(['drug', 'user'])->whereHas('drug', function ($query) use ($drugName) {
        $query->where('Drug_name', 'like', '%' . $drugName . '%');
    })->get();

    return response()->json($medications);
}
public function getMorningMedications()
{
    $medications = ConsumedMedication::with(['drug', 'user'])->where('period', 'morning')->get();

    if ($medications->isEmpty()) {
        return response()->json(['message' => 'No medications found for morning period'], 404);
    }

    return response()->json($medications);
}

public function getAfternoonMedications()
{
    $medications = ConsumedMedication::with(['drug', 'user'])->where('period', 'afternoon')->get();

    if ($medications->isEmpty()) {
        return response()->json(['message' => 'No medications found for afternoon period'], 404);
    }

    return response()->json($medications);
}

public function getEveningMedications()
{
    $medications = ConsumedMedication::with(['drug', 'user'])->where('period', 'evening')->get();

    if ($medications->isEmpty()) {
        return response()->json(['message' => 'No medications found for evening period'], 404);
    }

    return response()->json($medications);
}
 public function show($id)
    {
        $userId = Auth::id();
        $consumedMedication = ConsumedMedication::where('User_ID', $userId)->with(['drug', 'user'])->findOrFail($id);
        return response()->json($consumedMedication);
    }
    public function update(Request $request, $id)
    {
        $consumedMedication = ConsumedMedication::findOrFail($id);
        $drug = $consumedMedication->drug;
    
        $request->validate([
            'Drug_ID' => 'sometimes|exists:drugs,id',
            'Doctor_Name' => 'sometimes|string|max:255',
            'Date_Prescribed' => 'nullable|string',
            'period' => 'sometimes|in:morning,afternoon,evening',
            'Drug_name' => 'sometimes|string|max:255',
            'Effective_Material' => 'sometimes|string|max:255',
            'Side_Effects' => 'sometimes|string|max:255',
            'Other_Information' => 'sometimes|string|max:255',
            'image' => 'sometimes|string',
        ]);
    
        $medicationData = [];
        $drugData = [];
    
        if ($request->has('Doctor_Name')) {
            $medicationData['Doctor_Name'] = $request->Doctor_Name;
        }
    
        if ($request->has('Date_Prescribed')) {
            $medicationData['Date_Prescribed'] = $request->Date_Prescribed;
        }
    
        if ($request->has('period')) {
            $medicationData['period'] = $request->period;
        }
    
        if ($request->has('Drug_name')) {
            $drugData['Drug_name'] = $request->Drug_name;
        }
    
        if ($request->has('Effective_Material')) {
            $drugData['Effective_Material'] = $request->Effective_Material;
        }
    
        if ($request->has('Side_Effects')) {
            $drugData['Side_Effects'] = $request->Side_Effects;
        }
    
        if ($request->has('Other_Information')) {
            $drugData['Other_Information'] = $request->Other_Information;
        }
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $drugData['image'] = $imagePath;
        }
    
        // Update the drug information
        if (!empty($drugData)) {
            $drug->update($drugData);
        }
    
        // Update the consumed medication information
        if (!empty($medicationData)) {
            $consumedMedication->update($medicationData);
        }
    
        $consumedMedication->load('drug');
    
        return response()->json($consumedMedication);
    }
    
    
    public function destroy($id)
    {
        $userId = Auth::id();
        $consumedMedication = ConsumedMedication::where('User_ID', $userId)->findOrFail($id);
        $consumedMedication->delete();

        return response()->json(null, 204);
    }
}
