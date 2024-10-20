<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    use HasFactory;

    protected $table = 'subject_teachers';

    protected $fillable = [
        'subject_group_id',
        'subject_id',
        'class_id',
        'section_id',
        'user_id',
    ];

    /**
     * Define the relationship with the Subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Define the relationship with the Class.
     */
    public function class()
    {
        return $this->belongsTo(Classg::class); 
    }

    /**
     * Define the relationship with the Section.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Define the relationship with the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjectgroup()
    {
        return $this->belongsTo(SubjectGroup::class);
    }

}
