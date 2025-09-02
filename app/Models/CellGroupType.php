<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CellGroupType extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * Relationship to cell groups
     */
    public function cellGroups()
    {
        return $this->hasMany(CellGroup::class);
    }
}
