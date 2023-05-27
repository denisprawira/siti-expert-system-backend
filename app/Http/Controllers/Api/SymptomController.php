<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Symptom;

class SymptomController extends Controller
{
    public function index()
    {
        $allSymptoms = Symptom::all();
        return $allSymptoms;
        // Your code here
    }


    public function getSymptomDiseases(){
        $symptoms = Symptom::with('diseases')->get();
        
        return response()->json($symptoms);
    }
}
