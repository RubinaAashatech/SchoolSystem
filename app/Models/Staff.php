<?php


namespace App\Models;


use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Staff extends Model
{
    protected $table = 'staffs';
    use HasFactory;


    protected $fillable = ['user_id', 'school_id', 'employee_id', 'department_id', 'qualification', 'work_experience', 'marital_status', 'date_of_joining', 'date_of_leaving', 'payscale', 'basic_salary', 'contract_type', 'shift', 'location', 'resume', 'joining_letter', 'resignation_letter', 'medical_leave', 'casual_leave', 'maternity_leave', 'other_document', 'role', 'is_active'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function staffAttendance()
    {
        return $this->hasOne(StaffAttendance::class, 'staff_id');
    }


    public function staffsAttendances()
    {
        return $this->hasMany(StaffAttendance::class, 'staff_id');
    }
     // Users with this role
     public function users()
     {
         return $this->hasMany(User::class, 'role_id');
     }


     public function school()
     {
         return $this->belongsTo(School::class); // Adjust the class path if needed
     }


     public function role()
{
    return $this->belongsTo(Role::class);
}


}

