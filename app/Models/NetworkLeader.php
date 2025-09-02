<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkLeader extends Model
{
    protected $fillable = [
        'church_attender_id',
        'network',
        'leader_name'
    ];

    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }
}
