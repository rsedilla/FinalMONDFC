<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CellGroupType;

class CellGroupTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cellGroupTypes = [
            [
                'name' => 'Men\'s Group',
                'description' => 'Cell groups specifically for men to focus on men\'s ministry and discipleship',
            ],
            [
                'name' => 'Women\'s Group',
                'description' => 'Cell groups specifically for women to focus on women\'s ministry and fellowship',
            ],
            [
                'name' => 'Youth Group',
                'description' => 'Cell groups for teenagers and young adults (ages 13-25)',
            ],
            [
                'name' => 'Married Couples',
                'description' => 'Cell groups for married couples to strengthen family relationships and marriage',
            ],
            [
                'name' => 'Young Professionals',
                'description' => 'Cell groups for working professionals and young career-focused individuals',
            ],
            [
                'name' => 'Senior Saints',
                'description' => 'Cell groups for senior church members and retirees',
            ],
            [
                'name' => 'Mixed Group',
                'description' => 'Mixed gender cell groups for general fellowship and discipleship',
            ],
            [
                'name' => 'Singles Group',
                'description' => 'Cell groups for single individuals to build community and support',
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
