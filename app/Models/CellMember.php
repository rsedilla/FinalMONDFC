<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CellMember extends Model
{
    protected $fillable = [
        'church_attender_id',
        'cell_group_id',
        'training_progress_id',
    ];

    /**
     * Relationship to the original church attender record
     */
    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }

    /**
     * Relationship to the cell group
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
     * Get the full name from the church attender
     */
    public function getFullNameAttribute()
    {
        return $this->churchAttender?->full_name;
    }

    /**
     * Get the email from the church attender
     */
    public function getEmailAttribute()
    {
        return $this->churchAttender?->email;
    }

    /**
     * Get the phone number from the church attender
     */
    public function getPhoneNumberAttribute()
    {
        return $this->churchAttender?->phone_number;
    }

    /**
     * Scope to include church attender data for performance
     */
    public function scopeWithChurchAttender($query)
    {
        return $query->with('churchAttender');
    }
}
