<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SundayServiceCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_attender_id',
        'service_number',
        'attendance_date',
        'notes',
    ];

    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }
}
