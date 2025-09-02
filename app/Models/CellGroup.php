<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CellGroup extends Model
{
    protected $fillable = [
        'cell_group_type_id',
        'meeting_time',
        'meeting_day',
        'location',
    ];

    /**
     * Relationship to cell group type
     */
    public function cellGroupType()
    {
        return $this->belongsTo(CellGroupType::class);
    }

    /**
     * Relationship to cell members
     */
    public function cellMembers()
    {
        return $this->hasMany(CellMember::class);
    }

    /**
     * Relationship to cell leaders
     */
    public function cellLeaders()
    {
        return $this->hasMany(CellLeader::class);
    }

    /**
     * Get display name for the cell group
     */
    public function getDisplayNameAttribute()
    {
        return $this->cellGroupType?->name . ' - ' . $this->meeting_day . ' ' . $this->meeting_time;
    }
}
