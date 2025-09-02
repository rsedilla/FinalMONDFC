<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CellGroupLessonCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_attender_id',
        'lesson_number',
        'completion_date',
        'notes',
    ];

    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }
}
