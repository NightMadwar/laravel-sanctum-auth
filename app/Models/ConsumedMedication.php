<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumedMedication extends Model
{
    use HasFactory;
    protected $fillable = [
        'Drug_ID',
        'User_ID',
        'Doctor_Name',
        'Date_Prescribed',
        'period',
        
    ];

    public function drug()
    {
        return $this->belongsTo(Drug::class, 'Drug_ID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'User_ID');
    }
}
