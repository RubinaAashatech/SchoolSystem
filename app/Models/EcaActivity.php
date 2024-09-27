<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcaActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'player_type',
        'is_active',
        'eca_head_id',
        'pdf_image',
        'created_by'
    ];

    public function ecaHead()
    {
        return $this->belongsTo(ExtraCurricularHead::class, 'eca_head_id');
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'eca_activity_school', 'eca_activity_id', 'school_id');
    }

    public function participants()
    {
        return $this->hasMany(EcaParticipation::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classg::class, 'eca_activity_class', 'eca_activity_id', 'class_id');
    }

    public function isMunicipalityCreated()
    {
        return optional($this->ecaHead)->user->role === 'municipality';
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

}
