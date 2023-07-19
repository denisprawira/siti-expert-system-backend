<?php

namespace App\Inference;
use App\Models\Disease;

class InferenceEngine
{
    public function forwardChaining($disease_symptoms,$initial_symptoms){
        //for populate present symptoms of each disease
        $diseaseSymptomWeights = array();
        //total symptom's weight of disease after normalization 
        $diseasesWeight= array();
        //total weight of each disease symptom that present after normalization
            $totalWeight=0;
            //array of final result 
            $result = array();
            $diseasePercentages = array();
        $response = array();

        //populate disease and symptom that persent 
        foreach($disease_symptoms as $disease => $symptoms){
            foreach($initial_symptoms as $initialSymptom){
                if (isset($symptoms[$initialSymptom])) {
                    if (!array_key_exists($disease, $diseaseSymptomWeights)) {
                        $diseaseSymptomWeights[$disease] = array($initialSymptom => $symptoms[$initialSymptom]);
                    } else {
                        $diseaseSymptomWeights[$disease][$initialSymptom] = $symptoms[$initialSymptom];
                    }
                }
            }
        }
// 1,2,3 
// gejala 1 di hepatitis 1 - > 0.5 / total weight /bobot di hepatitis 1 0,1 
// 0.5 
// 
// berapa probabilitas dari masing" hepatitis berdasarkan symptom yang ada?
// 
// setiap hepatitis mencari symtom yang ada di list mereka merujuk symptom yang di input / initial symptom
// hitung join probability probabilitas gabungan dari masing" hepatitis



        //normalize weight value
        foreach($diseaseSymptomWeights as $disease => $symptoms){
            $total =0;
            
            $sumOfWeights = Disease::findOrFail($disease)->symptoms()->sum('weight');
            foreach($symptoms as $symptom =>$value){
                $total += $value['weight']/$sumOfWeights;
            }     
            $diseasesWeight[$disease] = $total;
            $totalWeight +=$diseasesWeight[$disease];
        }

        foreach($diseasesWeight as $disease => $val){
            $diseasePercentages[$disease] = $val/$totalWeight*100;
            
        }

        foreach($diseaseSymptomWeights as $disease => $symptoms){
            $index = array();
            $keys = array_keys($symptoms);
            $index['symptoms'] = $keys;
            if (array_key_exists($disease, $diseasePercentages)) {
                $result[$disease]["percentage"] = $diseasePercentages[$disease];
                $result[$disease]["symptoms"]= $keys;
            } 

        }

        $response["initial_symptoms"] = $initial_symptoms;
        $response["result"] = $result;

        $responseData = [
            'initial_symptoms' => $response["initial_symptoms"],
            'result' => $response["result"]
        ];

         return $responseData;
       
    }
}