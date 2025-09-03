<?php

namespace App\Services\ChurchAttender;

use App\Models\ChurchAttender;
use App\Models\NetworkLeader;
use App\Services\PromotionRequirementsChecker;

class ChurchAttenderProgressService
{
    public function __construct(
        private PromotionRequirementsChecker $requirementsChecker
    ) {}

    public function getNetworkOptions(): array
    {
        return NetworkLeader::pluck('leader_name', 'leader_name')->toArray();
    }

    public function getNetworkColors(): array
    {
        return [
            'mens' => 'blue',
            'womens' => 'pink',
        ];
    }

    public function getSuynlProgress(ChurchAttender $record): string
    {
        $completed = $record->suynlLessonCompletions()->count();
        return "{$completed}/10";
    }

    public function getSundayServiceProgress(ChurchAttender $record): string
    {
        $completed = $record->sundayServiceCompletions()->count();
        return "{$completed}/4";
    }

    public function getCellGroupProgress(ChurchAttender $record): string
    {
        $completed = $record->cellGroupLessonCompletions()->count();
        return "{$completed}/4";
    }

    public function getProgressColor(ChurchAttender $record, string $type): string
    {
        $completed = match($type) {
            'suynl' => $record->suynlLessonCompletions()->count(),
            'sunday_service' => $record->sundayServiceCompletions()->count(),
            'cell_group' => $record->cellGroupLessonCompletions()->count(),
            default => 0
        };

        $required = match($type) {
            'suynl' => 10,
            'sunday_service' => 4,
            'cell_group' => 4,
            default => 1
        };

        return $completed >= $required ? 'success' : 'warning';
    }

    public function getPromotionStatus(ChurchAttender $record): string
    {
        $requirements = $this->requirementsChecker->checkCellMemberRequirements($record);
        return $this->requirementsChecker->getRequirementsSummary($requirements);
    }

    public function canPromote(ChurchAttender $record): bool
    {
        if ($record->isPromoted()) {
            return false;
        }

        // You can implement additional promotion logic here
        // For now, delegate to the requirements checker
        return $this->requirementsChecker->checkCellMemberRequirements($record)['can_promote'] ?? false;
    }
}
