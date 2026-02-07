<?php

namespace App\Http\Controllers;

use App\Models\Costumer;
use App\Models\Motif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class costumercontroller extends Controller
{
    public function index(){
        $costumers = Costumer::with('motifs')->get();
        
        $costumers->each(function ($costumer) {
            $costumer->motifs_count = $costumer->motifs->count();
        });
        
        return response()->json(['costumers' => $costumers], 200);
    }
    
    public function show($id){
        $costumer = Costumer::find($id);
        if (!$costumer) {
            return response()->json(['message' => 'Costumer not found'], 404);
        }
        return response()->json(['motifs' => $costumer->motifs], 200);
    }

    public function motifs($id){
        $costumer = Costumer::find($id);
        if (!$costumer) {
            return response()->json(['message' => 'Costumer not found'], 404);
        }
        $motifs = $costumer->motifs;
        return response()->json(['motifs' => $motifs], 200);
    }
    
    public function store(Request $request){
        Log::info('📝 STORE REQUEST RECEIVED:', $request->all());
        
        try {
            $validatedData = $request->validate([
                'number' => 'required|string',
                'nom' => 'nullable|string',
                'description' => 'required|string',
                'evidence_image' => 'nullable|string', // Accept image URL
            ]);
            
            Log::info('✅ VALIDATION PASSED:', $validatedData);
            
            // Check if customer already exists with this number
            $costumer = Costumer::where('number', $validatedData['number'])->first();
            
            if (!$costumer) {
                // Customer doesn't exist, create new one
                Log::info('🔄 Creating NEW customer...');
                $costumer = Costumer::create([
                    'number' => $validatedData['number'],
                ]);
                Log::info('✅ New customer created:', $costumer->toArray());
            } else {
                Log::info('✅ Customer already exists:', $costumer->toArray());
            }
            
            // Get user_id
            $user_id = $request->user_id ?? 1;
            
            // Create motif (comment) for this customer
            Log::info('🔄 Creating motif...');
            $motif = Motif::create([
                'description' => $validatedData['description'],
                'evidence_image' => $validatedData['evidence_image'] ?? null,
                'user_id' => $user_id,
                'costumer_id' => $costumer->id,
            ]);
            
            Log::info('✅ Motif created:', $motif->toArray());
            
            return response()->json([
                'message' => 'Successfully saved',
                'costumer' => $costumer,
                'motif' => $motif
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('❌ Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Error: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    function updateComment(Request $request, $id)
    {
        $motif = Motif::find($id);
        if (!$motif) {
            return response()->json(['message' => 'Motif not found'], 404);
        }

        $validatedData = $request->validate([
            'description' => 'required|string',
            'evidence_image' => 'nullable|string', // Allow updating evidence image
        ]);

        $motif->update($validatedData);

        return response()->json(['message' => 'Motif updated successfully', 'motif' => $motif], 200);
    }

    function deleteComment($id)
    {
        $motif = Motif::find($id);
        if (!$motif) {
            return response()->json(['message' => 'Motif not found'], 404);
        }

        $motif->delete();

        return response()->json(['message' => 'Motif deleted successfully'], 200);
    }
}