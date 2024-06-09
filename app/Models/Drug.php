<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory; 
    protected $fillable = ['Drug_name','Effective_Material','Side_Effects','Other_Information','image'];
}
