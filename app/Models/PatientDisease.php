<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientDisease extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'patient_id',
        'disease_id',
        'probability'
    
    ];
}
