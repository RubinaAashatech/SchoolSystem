<?php

namespace App\Models;

use App\Models\Classg;
use App\Models\FeeDue;
use App\Models\StudentLeave;
use App\Models\AssignClassTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    // protected $table = 'sections';
    protected $fillable = ['section_name', 'is_active',];  //school_id removed for a while
    public function sectionmanagement()
    {
        return $this->hasMany(AssignClassTeacher::class, 'section_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classg::class, 'class_sections', 'section_id', 'class_id')
                    ->withPivot('school_id');
    }
    public function classSections()
    {
        return $this->hasMany(ClassSection::class);
    }
    public function studentLeaves()
    {
        return $this->hasMany(StudentLeave::class, 'section_id');
    }
    public function feeDues()
    {
        return $this->hasMany(FeeDue::class, 'section_id');
    }
}
