<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuynlLessonCompletion extends Model
{
    protected $fillable = [
        'church_attender_id',
        'lesson_number',
        'completion_date'
    ];

    protected $casts = [
        'completion_date' => 'date',
    ];

    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }
}
