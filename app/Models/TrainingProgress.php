<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingProgress extends Model
{
    protected $table = 'training_progress';

    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }
}
