<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CellGroup;
use App\Models\CellGroupType;

class CellGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get cell group types
        $mensGroupType = CellGroupType::where('name', 'Men\'s Group')->first();
        $womensGroupType = CellGroupType::where('name', 'Women\'s Group')->first();
        $youthGroupType = CellGroupType::where('name', 'Youth Group')->first();
        $mixedGroupType = CellGroupType::where('name', 'Mixed Group')->first();

        if (!$mensGroupType || !$womensGroupType || !$youthGroupType || !$mixedGroupType) {
            $this->command->warn('Cell group types not found. Please run CellGroupTypeSeeder first.');
            return;
        }

        $sampleCellGroups = [
            [
                'cell_group_type_id' => $mensGroupType->id,
                'meeting_day' => 'Saturday',
                'meeting_time' => '09:00:00',
                'location' => 'Church Fellowship Hall - Room A',
            ],
            [
                'cell_group_type_id' => $womensGroupType->id,
                'meeting_day' => 'Wednesday',
                'meeting_time' => '19:00:00',
                'location' => 'Church Fellowship Hall - Room B',
            ],
            [
                'cell_group_type_id' => $youthGroupType->id,
                'meeting_day' => 'Friday',
                'meeting_time' => '19:30:00',
                'location' => 'Youth Center - Main Room',
            ],
            [
                'cell_group_type_id' => $mixedGroupType->id,
                'meeting_day' => 'Sunday',
                'meeting_time' => '14:00:00',
                'location' => 'Pastor\'s Home - Living Room',
            ],
            [
                'cell_group_type_id' => $mensGroupType->id,
                'meeting_day' => 'Tuesday',
                'meeting_time' => '20:00:00',
                'location' => 'Community Center - Conference Room',
            ],
            [
                'cell_group_type_id' => $womensGroupType->id,
                'meeting_day' => 'Thursday',
                'meeting_time' => '10:00:00',
                'location' => 'Church Library',
            ],
        ];

        foreach ($sampleCellGroups as $cellGroup) {
            CellGroup::updateOrCreate(
                [
                    'cell_group_type_id' => $cellGroup['cell_group_type_id'],
                    'meeting_day' => $cellGroup['meeting_day'],
                    'meeting_time' => $cellGroup['meeting_time'],
                ],
                ['location' => $cellGroup['location']]
            );
        }

        $this->command->info('Sample cell groups created successfully!');
    }
}
