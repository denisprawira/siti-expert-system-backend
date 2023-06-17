<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'name',
        'age', 
        'email',  
        'address',
        'sex'
    ];
}
