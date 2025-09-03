<?php

namespace App\Services;

use App\Models\ChurchAttender;
use App\Models\CellMember;
use App\Models\TrainingProgress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChurchMemberPromotionService
{
    private PromotionRequirementsChecker $requirementsChecker;

    public function __construct(PromotionRequirementsChecker $requirementsChecker)
    {
        $this->requirementsChecker = $requirementsChecker;
    }

    /**
     * Promote a church attender to cell member
     */
    public function promoteToCellMember(ChurchAttender $attender, int $cellGroupId): bool
    {
        // Check if requirements are met
        $requirements = $this->requirementsChecker->checkCellMemberRequirements($attender);
        
        if (!$requirements['all_met']) {
            throw new \Exception('Church attender does not meet promotion requirements');
        }

        // Check if already promoted
        if ($attender->promoted_at) {
            throw new \Exception('Church attender has already been promoted');
        }

        // Use database transaction for data integrity
        return DB::transaction(function () use ($attender, $cellGroupId) {
            try {
                // Update existing training progress to LIFE CLASS (ID: 2) instead of creating new
                $existingProgress = TrainingProgress::where('church_attender_id', $attender->id)->first();
                
                if ($existingProgress) {
                    // Update from SUYNL (ID: 1) to LIFE CLASS (ID: 2)
                    $existingProgress->update([
                        'training_progress_type_id' => 2, // LIFE CLASS
                    ]);
                    $trainingProgress = $existingProgress;
                } else {
                    // If no existing progress, create new one with LIFE CLASS
                    $trainingProgress = TrainingProgress::create([
                        'church_attender_id' => $attender->id,
                        'training_progress_type_id' => 2, // LIFE CLASS
                    ]);
                }

                // Create cell member record
                $cellMember = CellMember::create([
                    'church_attender_id' => $attender->id,
                    'cell_group_id' => $cellGroupId,
                    'training_progress_id' => $trainingProgress->id,
                ]);

                // Mark church attender as promoted
                $attender->update([
                    'promoted_at' => now(),
                    'promoted_to' => 'cell_member',
                    'promoted_to_id' => $cellMember->id,
                ]);

                // Log the promotion
                Log::info('Church attender promoted to cell member with LIFE CLASS training', [
                    'church_attender_id' => $attender->id,
                    'cell_member_id' => $cellMember->id,
                    'cell_group_id' => $cellGroupId,
                    'training_progress_id' => $trainingProgress->id,
                    'training_progress_type_id' => 2, // LIFE CLASS
                    'promoted_at' => now(),
                ]);

                return true;

            } catch (\Exception $e) {
                Log::error('Failed to promote church attender to cell member', [
                    'church_attender_id' => $attender->id,
                    'cell_group_id' => $cellGroupId,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        });
    }

    /**
     * Check if an attender can be promoted
     */
    public function canPromoteToCellMember(ChurchAttender $attender): bool
    {
        if ($attender->promoted_at) {
            return false;
        }

        $requirements = $this->requirementsChecker->checkCellMemberRequirements($attender);
        return $requirements['all_met'];
    }

    /**
     * Get promotion status for an attender
     */
    public function getPromotionStatus(ChurchAttender $attender): array
    {
        if ($attender->promoted_at) {
            return [
                'can_promote' => false,
                'status' => 'Already promoted',
                'promoted_at' => $attender->promoted_at,
                'promoted_to' => $attender->promoted_to,
            ];
        }

        $requirements = $this->requirementsChecker->checkCellMemberRequirements($attender);
        
        return [
            'can_promote' => $requirements['all_met'],
            'status' => $this->requirementsChecker->getRequirementsSummary($requirements),
            'requirements' => $requirements,
        ];
    }

    /**
     * Bulk check promotion eligibility for multiple attenders (performance optimized)
     */
    public function bulkCheckPromotionEligibility(array $attenderIds): array
    {
        // Get attenders with all relationships in one query
        $attenders = ChurchAttender::whereIn('id', $attenderIds)
            ->whereNull('promoted_at')
            ->with([
                'suynlLessonCompletions',
                'sundayServiceCompletions',
                'cellGroupLessonCompletions'
            ])
            ->get();

        $results = [];
        foreach ($attenders as $attender) {
            $results[$attender->id] = $this->canPromoteToCellMember($attender);
        }

        return $results;
    }

    /**
     * Reverse a promotion (for administrative purposes)
     */
    public function reversePromotion(ChurchAttender $attender): bool
    {
        if (!$attender->promoted_at) {
            throw new \Exception('Church attender has not been promoted');
        }

        return DB::transaction(function () use ($attender) {
            try {
                // Find and delete the cell member record
                if ($attender->promoted_to === 'cell_member' && $attender->promoted_to_id) {
                    $cellMember = CellMember::find($attender->promoted_to_id);
                    if ($cellMember) {
                        $cellMember->delete();
                    }
                }

                // Reset promotion status
                $attender->update([
                    'promoted_at' => null,
                    'promoted_to' => null,
                    'promoted_to_id' => null,
                ]);

                Log::info('Church attender promotion reversed', [
                    'church_attender_id' => $attender->id,
                    'reversed_at' => now(),
                ]);

                return true;

            } catch (\Exception $e) {
                Log::error('Failed to reverse church attender promotion', [
                    'church_attender_id' => $attender->id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        });
    }
}
