<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcaResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'eca_participation_id',
        'result_type',
        'description',
        'is_publish',
    ];

    public function ecaParticipation()
    {
        return $this->belongsTo(EcaParticipation::class);
    }
}
