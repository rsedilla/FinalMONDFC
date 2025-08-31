<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingProgress;

class ChurchAttender extends Model
{
    protected $table = 'church_attenders';

    public function trainingProgresses()
    {
        return $this->hasMany(TrainingProgress::class);
    }
}
