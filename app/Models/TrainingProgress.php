<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingProgress extends Model
{
    protected $table = 'training_progress';

    protected $fillable = [
        'church_attender_id',
        'training_progress_type_id',
    ];

    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }

    public function trainingProgressType()
    {
        return $this->belongsTo(TrainingProgressType::class);
    }
}
