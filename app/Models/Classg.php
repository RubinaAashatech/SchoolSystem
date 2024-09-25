<?php

namespace App\Models;

use App\Models\FeeDue;
use App\Models\Section;
use App\Models\StudentLeave;
use App\Models\SubjectGroup;
use App\Models\AssignClassTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classg extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['class', 'is_active', 'school_id'];

    public function assignclassteachers()
    {
        return $this->hasMany(AssignClassTeacher::class, 'class_id');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'class_sections', 'class_id', 'section_id')
                    ->withPivot('school_id');
    }

    public function subjectGroups()
    {
        return $this->hasMany(SubjectGroup::class, 'class_id');
    }

    public function studentLeaves()
    {
        return $this->hasMany(StudentLeave::class, 'class_id');
    }

    public function feeDues()
    {
        return $this->hasMany(FeeDue::class, 'class_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
{
    return $this->hasMany(Student::class, 'class_id');
}

public function isSelectedForActivity()
{
    return $this->activities()->where('id', $this->pivot->activity_id)->exists();
}
}
