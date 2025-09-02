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
        'birthday',
        'promoted_at',
        'promoted_to',
        'promoted_to_id',
    ];

    protected $casts = [
        'birthday' => 'date',
        'promoted_at' => 'datetime',
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

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
    }

    // Scopes for filtering members by promotion status

    /**
     * Scope to get only non-promoted church attenders
     */
    public function scopeNotPromoted($query)
    {
        return $query->whereNull('promoted_at');
    }

    /**
     * Scope to get only promoted church attenders
     */
    public function scopePromoted($query)
    {
        return $query->whereNotNull('promoted_at');
    }

    /**
     * Scope to get church attenders promoted to a specific role
     */
    public function scopePromotedTo($query, string $role)
    {
        return $query->where('promoted_to', $role);
    }

    // Helper methods for promotion

    /**
     * Check if this church attender has been promoted
     */
    public function isPromoted(): bool
    {
        return !is_null($this->promoted_at);
    }

    /**
     * Get the promotion status
     */
    public function getPromotionStatus(): string
    {
        if (!$this->isPromoted()) {
            return 'Active Church Attender';
        }

        return 'Promoted to ' . ucwords(str_replace('_', ' ', $this->promoted_to)) . ' on ' . $this->promoted_at->format('M j, Y');
    }

    /**
     * Relationship to cell member record (if promoted)
     */
    public function cellMember()
    {
        return $this->hasOne(CellMember::class);
    }
}
