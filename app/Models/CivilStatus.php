<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CivilStatus extends Model
{
    protected $fillable = ['name'];

    public function churchAttenders()
    {
        return $this->hasMany(ChurchAttender::class);
    }
}
