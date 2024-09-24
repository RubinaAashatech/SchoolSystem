<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{

    use HasFactory;

    protected $fillable = ['head_school', 'state_id', 'district_id', 'municipality_id', 'ward_id', 'school_type', 'school_code', 'name', 'address', 'phone_number', 'email', 'logo', 'emergency_contact', 'bank_name', 'bank_account_no', 'bank_branch', 'disable_reason', 'facebook', 'twitter', 'linkedin', 'instagram', 'website', 'note', 'disable_at', 'verification_code', 'is_active', 'group_id'];

    /**
     * Getting the district that the user belongs to.
     */
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function district_bystate($state_id)
    {
        // dd($state_id);
        return District::select('name', 'id', 'province_id as state_id')->where('province_id', $state_id)->get();
    }

    public function municipalitiesByDistrict($district_id)
    {
        return Municipality::select('name', 'id', 'district_id', 'wards')->where('district_id', $district_id)->get();
    }

    public function wardsByMunicipality($municipality_id)
    {
        $municipality = Municipality::find($municipality_id);

        if ($municipality) {
            return json_decode($municipality->wards);
        }

        return [];
    }

    public function group()
    {
        return $this->belongsTo(SchoolGroup::class, 'group_id');

    }

    public function staffs()
    {
        return $this->hasMany(Staff::class, 'school_id');

    }
    public function students()
    {
        return $this->hasMany(Student::class, 'school_id');

    }

    public function studentSessions()
    {
        return $this->hasMany(StudentSession::class, 'school_id');
    }

    public function studentAttendances()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function staffAttendances()
    {
        return $this->hasMany(StaffAttendance::class);
    }

    public function headTeacherLogs()
    {
        return $this->hasMany(HeadTeacherLog::class);
    }

    public function ecaActivities()
    {
        return $this->belongsToMany(EcaActivity::class, 'eca_activity_school');
    }

    public function isSelectedForActivity()
{
    return $this->activities()->where('id', $this->pivot->activity_id)->exists();
}
}


