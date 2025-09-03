<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class G12Leader extends Model
{
    protected $fillable = [
        'church_attender_id',
        'cell_group_id',
        'training_progress_id',
    ];

    /**
     * Relationship to church attender
     */
    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }

    /**
     * Relationship to cell group
     */
    public function cellGroup()
    {
        return $this->belongsTo(CellGroup::class);
    }

    /**
     * Relationship to training progress
     */
    public function trainingProgress()
    {
        return $this->belongsTo(TrainingProgress::class);
    }

    /**
     * Get the full name of the G12 leader
     */
    public function getFullNameAttribute()
    {
        return $this->churchAttender ? $this->churchAttender->getFullNameAttribute() : 'Unknown';
    }
}
