<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name', 
        'definition',  
        'solution'
    ];

    //many to many
    //belongsToMany parameter : belongsToMany(1,2,3,4)
    //1. model from another master table
    //2. pivot table
    //3. this table ID as foreign key in pivot table
    //4.  foreign key name of the model that you are joining to
    public function Symptoms(){
        return $this->belongsToMany(Symptom::class, 'disease_symptoms','disease_id','symptom_id')->withPivot('weight');
       
    }
}
