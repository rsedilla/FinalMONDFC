<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\CellGroupType;

class CellGroupTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    // Remove all existing types to ensure only the specified ones remain
        // Remove all cell groups to avoid foreign key constraint
        DB::table('cell_groups')->delete();

        // Delete all cell group types except the ones we want to keep
        DB::table('cell_group_types')
            ->whereNotIn('name', ['Open Cell', 'Discipleship Cell', 'G12 Cell'])
            ->delete();
        $cellGroupTypes = [
            [
                'name' => 'Open Cell',
                'description' => 'A cell group open to all members for general fellowship and discipleship',
            ],
            [
                'name' => 'Discipleship Cell',
                'description' => 'A cell group focused on discipleship and spiritual growth',
            ],
            [
                'name' => 'G12 Cell',
                'description' => 'A cell group following the G12 vision and structure',
            ],
        ];

        foreach ($cellGroupTypes as $type) {
            CellGroupType::updateOrCreate(
                ['name' => $type['name']],
                ['description' => $type['description']]
            );
        }
    }
}
