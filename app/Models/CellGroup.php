<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CellGroup extends Model
{
    protected $fillable = [
        'cell_group_type_id',
        'meeting_time',
        'meeting_day',
        'location',
        'leader_id',
        'leader_type',
    ];

    /**
     * Boot method to automatically generate CellGroupID
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cellGroup) {
            $cellGroup->CellGroupID = self::generateCellGroupID();
        });
    }

    /**
     * Generate CellGroupID in format YYYYMM###
     */
    private static function generateCellGroupID(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $prefix = $year . $month;

        // Get the highest counter for current year and month
        $lastCellGroup = self::where('CellGroupID', 'LIKE', $prefix . '%')
            ->orderBy('CellGroupID', 'desc')
            ->first();

        if ($lastCellGroup) {
            // Extract the counter from the last CellGroupID
            $lastCounter = (int) substr($lastCellGroup->CellGroupID, -3);
            $counter = $lastCounter + 1;
        } else {
            $counter = 1;
        }

        // Format counter with leading zeros (3 digits)
        $formattedCounter = str_pad($counter, 3, '0', STR_PAD_LEFT);

        return $prefix . $formattedCounter;
    }

    /**
     * Polymorphic relationship to leader (Cell Leader or G12 Leader)
     */
    public function leader()
    {
        return $this->morphTo('leader', 'leader_type', 'leader_id');
    }

    /**
     * Get the leader's name
     */
    public function getLeaderNameAttribute()
    {
        if ($this->leader) {
            return $this->leader->getFullNameAttribute();
        }
        return 'No Leader Assigned';
    }

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
