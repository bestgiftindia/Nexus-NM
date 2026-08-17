<?php

namespace Database\Seeders;

use App\Models\MissingNumber;
use App\Models\Planet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MissingNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/data/missing_numbers_table.json')), true);

        foreach ($data as $row) {
            MissingNumber::create([
                'king_planet_id'   => $row['missing_number'],
                'missing_number_msg'    => $row['missing_number_msg'],
                'repetitive_number_donation'    => $row['repetitiveNumberDonation'],
                'repetitive_number_medicalIssues'    => $row['repetitiveNumberMedicalIssues'],
                'remedies'    => $row['remedies'],
            ]);
        }
    }
}
