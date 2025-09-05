<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patients extends Model
{
    use HasFactory;

    protected $fillable = [
       'hospital_id', 'name', 'address', 'phone',
    ];

    public function hospital()
    {
        return $this->belongsTo(Hospitals::class);
    }
}
