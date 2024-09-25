<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCurricularHead extends Model
{
    use HasFactory;
    protected $table = 'extra_curricular_heads';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
    public function inventories()
    {
        return $this->hasMany(Income::class, 'extra_curricular_heads_id');
    }

    public function ecaActivities()
    {
        return $this->hasMany(EcaActivity::class, 'eca_activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Assuming 'user_id' is the foreign key in your table
    }
}
