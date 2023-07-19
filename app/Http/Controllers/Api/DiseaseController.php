<?php

namespace App\Http\Controllers\Api;

use App\Models\Disease;
use App\Models\Patient;
use App\Models\PatientSymptom;
use App\Models\PatientDisease;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Inference\InferenceEngine;
use PDF;

class DiseaseController extends Controller
{
    protected $inferenceEngine;
    
    public function __construct(InferenceEngine $inferenceEngine)
    {
        $this->inferenceEngine = $inferenceEngine;
    }

    public function getDiseases()
    {
        $diseases = Disease::all();
        return response()->json($diseases);
    }

    public function getDiseaseSymptoms()
    {
        $diseases = Disease::with("symptoms")->get();
        $disease_symptoms = [];

        foreach ($diseases as $disease) {
            $symptoms = [];

            if ($disease->symptoms !== null) {
                foreach ($disease->symptoms as $disease_symptom) {
                    $symptoms[$disease_symptom->id] = [
                        "weight" => $disease_symptom->pivot->weight,
                    ];
                }
            }

            $disease_symptoms[$disease->id] = $symptoms;
        }

        return $disease_symptoms;
    }

    public function getDiagnose(Request $request)
    {       
        $validator = Validator::make($request->all(), [
            'data.patient.name'      => 'required',
            'data.patient.age'       => 'required',
            'data.patient.sex'       => 'required',
            'data.patient.address'   => 'required',
            'data.patient.email'     => 'required|email'

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $patientData = $request->input('data.patient');
        $patient = Patient::create($patientData);
        $patientId = $patient->id;
        $sympt = array();
        foreach($request->input('data.symptoms') as $symptom){
            $patientSymptom = PatientSymptom::create([
                'patient_id' => $patientId,
                'symptom_id' => $symptom
            ]);
        }


        $diseaseSymptoms = $this->getDiseaseSymptoms();
        $result = $this->inferenceEngine->forwardChaining($diseaseSymptoms, $request->input('data.symptoms'));


        if($result != null) {
            foreach($result['result'] as $key => $value) {
               
                $patientDisease = PatientDisease::create([
                    'patient_id' => $patientId,
                    'disease_id' => $key,
                    'probability' => $value['percentage']
                ]);
                
            }

        }

       $result['patient'] = $patient;
        return response()->json([
            "status"  => "success",
            "code"    => 200,
            "data" => $result
        ]);
    }

    public function downloadReport()
    {
        $pdf = PDF::loadView('diagnose-report');
        return $pdf->download('pdf_file.pdf');
    }
}
