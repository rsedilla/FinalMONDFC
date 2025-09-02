<?php

namespace App\Services;

use App\Models\ChurchAttender;
use Illuminate\Support\Collection;

class PromotionRequirementsChecker
{
    /**
     * Check if a church attender meets all requirements for promotion to cell member
     */
    public function checkCellMemberRequirements(ChurchAttender $attender): array
    {
        $requirements = [
            'suynl_lessons' => $this->checkSuynlLessons($attender),
            'sunday_services' => $this->checkSundayServices($attender),
            'cell_group_attendance' => $this->checkCellGroupAttendance($attender),
        ];

        $requirements['all_met'] = $requirements['suynl_lessons']['met'] && 
                                   $requirements['sunday_services']['met'] && 
                                   $requirements['cell_group_attendance']['met'];

        return $requirements;
    }

    /**
     * Check SUYNL lesson completion (10/10 required)
     */
    private function checkSuynlLessons(ChurchAttender $attender): array
    {
        $completedLessons = $attender->suynlLessonCompletions()->count();
        $required = 10;

        return [
            'met' => $completedLessons >= $required,
            'completed' => $completedLessons,
            'required' => $required,
            'description' => "SUYNL Lessons: {$completedLessons}/{$required}"
        ];
    }

    /**
     * Check Sunday Service attendance (4/4 required)
     */
    private function checkSundayServices(ChurchAttender $attender): array
    {
        $completedServices = $attender->sundayServiceCompletions()->count();
        $required = 4;

        return [
            'met' => $completedServices >= $required,
            'completed' => $completedServices,
            'required' => $required,
            'description' => "Sunday Services: {$completedServices}/{$required}"
        ];
    }

    /**
     * Check Cell Group attendance (4/4 required)
     */
    private function checkCellGroupAttendance(ChurchAttender $attender): array
    {
        $completedLessons = $attender->cellGroupLessonCompletions()->count();
        $required = 4;

        return [
            'met' => $completedLessons >= $required,
            'completed' => $completedLessons,
            'required' => $required,
            'description' => "Cell Group Lessons: {$completedLessons}/{$required}"
        ];
    }

    /**
     * Get a summary message for requirements
     */
    public function getRequirementsSummary(array $requirements): string
    {
        if ($requirements['all_met']) {
            return '✅ Ready for promotion to Cell Member';
        }

        $pending = [];
        foreach ($requirements as $key => $requirement) {
            if ($key !== 'all_met' && !$requirement['met']) {
                $pending[] = $requirement['description'];
            }
        }

        return '⏳ Pending: ' . implode(', ', $pending);
    }

    /**
     * Bulk check requirements for multiple attenders (performance optimized)
     */
    public function bulkCheckCellMemberRequirements(Collection $attenders): Collection
    {
        // Eager load all relationships in one go for performance
        $attenders->load([
            'suynlLessonCompletions',
            'sundayServiceCompletions',
            'cellGroupLessonCompletions'
        ]);

        return $attenders->map(function ($attender) {
            return [
                'attender_id' => $attender->id,
                'requirements' => $this->checkCellMemberRequirements($attender)
            ];
        });
    }
}
