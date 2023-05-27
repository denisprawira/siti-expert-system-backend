<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Disease;
use App\Inference\InferenceEngine;

class DiseaseController extends Controller
{
 
    
    public function getDiseases(){
        $diseases = Disease::all();
        return response()->json($diseases);
    }


    public function getDiseaseSymptoms(){
        $diseases = Disease::with('Symptoms')->get();
        $disease_symptoms = array();

        foreach ($diseases as $disease) {
            // Initialize an empty array to store the symptoms of this disease
            $symptoms = array();
            
            // Check if the disease has any symptoms
            if ($disease->symptoms !== null) {
                // Loop through each symptom of this disease and add it to the symptoms array
                foreach ( $disease->symptoms as $disease_symptom) {
                    $symptoms[$disease_symptom['id']] = [
                        // 'name' => $disease_symptom['name'],
                        'weight' => $disease_symptom->pivot->weight
                    ];
                }
            }
            
            // Add the disease name and its symptoms to the disease_symptoms array
            $disease_symptoms[$disease['id']] = $symptoms;
        }

        return $disease_symptoms;
    }


    public function getDiagnose(Request $request){
        $data = $request->json()->all();
        $inferenceEngine = new InferenceEngine();
        $initial = $data['data'];
        $diseaseSymptoms = $this->getDiseaseSymptoms();
        $result = $inferenceEngine->forwardChaining($diseaseSymptoms,$initial);
        // return $result;
    
        // Process the received array data and request ID
    
        // Return a response
        return response()->json([
            'result' => $result
        ]);
    }
}
