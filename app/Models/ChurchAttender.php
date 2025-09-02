<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingProgress;

class ChurchAttender extends Model
{
    public function cellGroupLessonCompletions()
    {
        return $this->hasMany(CellGroupLessonCompletion::class);
    }
    protected $table = 'church_attenders';

    protected $fillable = [
        'first_name',
        'middle_name', 
        'last_name',
        'email',
        'phone_number',
        'social_media_account',
        'network',
        'present_address',
        'permanent_address',
        'civil_status_id',
        'birthday'
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    public function trainingProgresses()
    {
        return $this->hasMany(TrainingProgress::class);
    }

    public function civilStatus()
    {
        return $this->belongsTo(CivilStatus::class);
    }

    public function suynlLessonCompletions()
    {
        return $this->hasMany(SuynlLessonCompletion::class);
    }

    public function sundayServiceCompletions()
    {
        return $this->hasMany(SundayServiceCompletion::class);
    }
}
