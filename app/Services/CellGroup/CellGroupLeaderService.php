<?php

namespace App\Services\CellGroup;

use App\Models\CellLeader;
use App\Models\G12Leader;
use Illuminate\Support\Collection;

class CellGroupLeaderService
{
    public function getLeaderOptions(): array
    {
        $options = [];
        
        // Get Cell Leaders with their church attenders in one query
        $cellLeaders = CellLeader::with('churchAttender')->get();
        foreach ($cellLeaders as $leader) {
            if ($leader->churchAttender) {
                $options["cell_leader_{$leader->id}"] = $leader->churchAttender->getFullNameAttribute() . ' (Cell Leader)';
            }
        }
        
        // Get G12 Leaders with their church attenders in one query
        $g12Leaders = G12Leader::with('churchAttender')->get();
        foreach ($g12Leaders as $leader) {
            if ($leader->churchAttender) {
                $options["g12_leader_{$leader->id}"] = $leader->churchAttender->getFullNameAttribute() . ' (G12 Leader)';
            }
        }
        
        return $options;
    }

    public function parseLeaderSelection(string $leaderSelection): array
    {
        $parts = explode('_', $leaderSelection);
        
        if (count($parts) < 3) {
            return ['leader_id' => null, 'leader_type' => null];
        }
        
        $type = $parts[0] . '_' . $parts[1]; // cell_leader or g12_leader
        $id = $parts[2];
        
        if ($type === 'cell_leader') {
            return [
                'leader_id' => $id,
                'leader_type' => CellLeader::class
            ];
        } elseif ($type === 'g12_leader') {
            return [
                'leader_id' => $id,
                'leader_type' => G12Leader::class
            ];
        }
        
        return ['leader_id' => null, 'leader_type' => null];
    }

    public function getDaysOfWeek(): array
    {
        return [
            'Monday' => 'Monday',
            'Tuesday' => 'Tuesday',
            'Wednesday' => 'Wednesday',
            'Thursday' => 'Thursday',
            'Friday' => 'Friday',
            'Saturday' => 'Saturday',
            'Sunday' => 'Sunday',
        ];
    }

    public function getDayColors(): array
    {
        return [
            'Sunday' => 'success',
            'Monday' => 'info',
            'Tuesday' => 'warning',
            'Wednesday' => 'danger',
            'Thursday' => 'secondary',
            'Friday' => 'primary',
            'Saturday' => 'gray',
        ];
    }
}
