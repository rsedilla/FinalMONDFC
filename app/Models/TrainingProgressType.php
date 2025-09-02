<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingProgressType extends Model
{
    protected $table = 'training_progress_types';

    protected $fillable = [
        'name',
        'description',
    ];

    public function trainingProgresses()
    {
        return $this->hasMany(TrainingProgress::class);
    }
}
