<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NetworkLeadersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mens = [
            'Albert Castro',
            'Daniel Oriel Ballano',
            'Darwin Dumael',
            'David Herald Felicelda',
            'Jeffrey Nel Figueroa',
            'John Benz Samson',
            'John Isaac Lausin',
            'Michael Roque',
            'Karl Nicholas Lisondra',
            'Raymond Sedilla',
            'Romeo Malificiar',
            'Virgilio Abogado',
        ];
        $womens = [
            'Ana Camille Polandaya',
            'Diane Grace Malificiar',
            'Dineriel Grace Felicelda',
            'Divina Ranay',
            'Eden Abogado',
            'Emelda Dalina',
            'Florie Ann Rivamonte',
            'Geraldine Ballano',
            'Joy Delen',
            'Jumelyn Torres',
            'Lilibeth Dorado',
            'Mary Grace Calilong',
            'Ranee Nicole Sedilla',
            'Rudgie Marie Teodocio',
            'Sierra Lee Manalo',
            'Victoria Roque',
        ];

        foreach ($mens as $name) {
            DB::table('network_leaders')->insert([
                'church_attender_id' => null, // Update with actual attender IDs if available
                'network' => 'mens',
                'leader_name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ($womens as $name) {
            DB::table('network_leaders')->insert([
                'church_attender_id' => null, // Update with actual attender IDs if available
                'network' => 'womens',
                'leader_name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
