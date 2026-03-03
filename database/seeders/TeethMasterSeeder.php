<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeethMasterSeeder extends Seeder
{
    public function run(): void
    {
        $teeth = [
            // Upper Right Quadrant (1-8)
            ['tooth_number' => 1, 'quadrant' => 'upper_right', 'standard_name' => 'Wisdom Tooth', 'alternate_name' => '3rd Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 2, 'quadrant' => 'upper_right', 'standard_name' => '12-year Molar', 'alternate_name' => '2nd Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 3, 'quadrant' => 'upper_right', 'standard_name' => '6-year Molar', 'alternate_name' => '1st Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 4, 'quadrant' => 'upper_right', 'standard_name' => '2nd PreMolar', 'alternate_name' => '2nd Bicuspid', 'tooth_type' => 'premolar'],
            ['tooth_number' => 5, 'quadrant' => 'upper_right', 'standard_name' => '1st PreMolar', 'alternate_name' => '1st Bicuspid', 'tooth_type' => 'premolar'],
            ['tooth_number' => 6, 'quadrant' => 'upper_right', 'standard_name' => 'Canine/Eye Tooth', 'alternate_name' => 'Cuspid', 'tooth_type' => 'canine'],
            ['tooth_number' => 7, 'quadrant' => 'upper_right', 'standard_name' => 'Lateral Incisor', 'alternate_name' => null, 'tooth_type' => 'incisor'],
            ['tooth_number' => 8, 'quadrant' => 'upper_right', 'standard_name' => 'Central Incisor', 'alternate_name' => null, 'tooth_type' => 'incisor'],

            // Upper Left Quadrant (9-16)
            ['tooth_number' => 9, 'quadrant' => 'upper_left', 'standard_name' => 'Central Incisor', 'alternate_name' => null, 'tooth_type' => 'incisor'],
            ['tooth_number' => 10, 'quadrant' => 'upper_left', 'standard_name' => 'Lateral Incisor', 'alternate_name' => null, 'tooth_type' => 'incisor'],
            ['tooth_number' => 11, 'quadrant' => 'upper_left', 'standard_name' => 'Canine/Eye Tooth', 'alternate_name' => 'Cuspid', 'tooth_type' => 'canine'],
            ['tooth_number' => 12, 'quadrant' => 'upper_left', 'standard_name' => '1st PreMolar', 'alternate_name' => '1st Bicuspid', 'tooth_type' => 'premolar'],
            ['tooth_number' => 13, 'quadrant' => 'upper_left', 'standard_name' => '2nd PreMolar', 'alternate_name' => '2nd Bicuspid', 'tooth_type' => 'premolar'],
            ['tooth_number' => 14, 'quadrant' => 'upper_left', 'standard_name' => '6-year Molar', 'alternate_name' => '1st Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 15, 'quadrant' => 'upper_left', 'standard_name' => '12-year Molar', 'alternate_name' => '2nd Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 16, 'quadrant' => 'upper_left', 'standard_name' => 'Wisdom Tooth', 'alternate_name' => '3rd Molar', 'tooth_type' => 'molar'],

            // Lower Left Quadrant (17-24)
            ['tooth_number' => 17, 'quadrant' => 'lower_left', 'standard_name' => 'Wisdom Tooth', 'alternate_name' => '3rd Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 18, 'quadrant' => 'lower_left', 'standard_name' => '12-year Molar', 'alternate_name' => '2nd Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 19, 'quadrant' => 'lower_left', 'standard_name' => '6-year Molar', 'alternate_name' => '1st Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 20, 'quadrant' => 'lower_left', 'standard_name' => '2nd PreMolar', 'alternate_name' => '2nd Bicuspid', 'tooth_type' => 'premolar'],
            ['tooth_number' => 21, 'quadrant' => 'lower_left', 'standard_name' => '1st PreMolar', 'alternate_name' => '1st Bicuspid', 'tooth_type' => 'premolar'],
            ['tooth_number' => 22, 'quadrant' => 'lower_left', 'standard_name' => 'Canine/Eye Tooth', 'alternate_name' => 'Cuspid', 'tooth_type' => 'canine'],
            ['tooth_number' => 23, 'quadrant' => 'lower_left', 'standard_name' => 'Lateral Incisor', 'alternate_name' => null, 'tooth_type' => 'incisor'],
            ['tooth_number' => 24, 'quadrant' => 'lower_left', 'standard_name' => 'Central Incisor', 'alternate_name' => null, 'tooth_type' => 'incisor'],

            // Lower Right Quadrant (25-32)
            ['tooth_number' => 25, 'quadrant' => 'lower_right', 'standard_name' => 'Central Incisor', 'alternate_name' => null, 'tooth_type' => 'incisor'],
            ['tooth_number' => 26, 'quadrant' => 'lower_right', 'standard_name' => 'Lateral Incisor', 'alternate_name' => null, 'tooth_type' => 'incisor'],
            ['tooth_number' => 27, 'quadrant' => 'lower_right', 'standard_name' => 'Canine/Eye Tooth', 'alternate_name' => 'Cuspid', 'tooth_type' => 'canine'],
            ['tooth_number' => 28, 'quadrant' => 'lower_right', 'standard_name' => '1st PreMolar', 'alternate_name' => '1st Bicuspid', 'tooth_type' => 'premolar'],
            ['tooth_number' => 29, 'quadrant' => 'lower_right', 'standard_name' => '2nd PreMolar', 'alternate_name' => '2nd Bicuspid', 'tooth_type' => 'premolar'],
            ['tooth_number' => 30, 'quadrant' => 'lower_right', 'standard_name' => '6-year Molar', 'alternate_name' => '1st Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 31, 'quadrant' => 'lower_right', 'standard_name' => '12-year Molar', 'alternate_name' => '2nd Molar', 'tooth_type' => 'molar'],
            ['tooth_number' => 32, 'quadrant' => 'lower_right', 'standard_name' => 'Wisdom Tooth', 'alternate_name' => '3rd Molar', 'tooth_type' => 'molar'],
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('teeth_master')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('teeth_master')->insert($teeth);
    }
}
