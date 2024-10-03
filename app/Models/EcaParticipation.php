<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EcaParticipation extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'eca_participations'; // Make sure this matches the table name in your database

    // Define which attributes are mass assignable
    protected $fillable = [
        'user_id',
        'school_id',
        'class_id',
        'section_id',
        'eca_activity_id',
        'participant_name',
    ];

    // Define relationships if needed
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function class()
    {
        return $this->belongsTo(Classg::class, 'class_id'); // Assuming the model is Class and the table is classes
    }


    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function ecaActivity()
    {
        return $this->belongsTo(EcaActivity::class, 'eca_activity_id');
    }

    public function ecaResults()
    {
        return $this->hasMany(EcaResult::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'user_id');
    }
}
