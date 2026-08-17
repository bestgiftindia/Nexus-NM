<?php

namespace Database\Seeders;

use App\Models\Mahadasha;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahadashaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/data/mahadasha_table.json')), true);

        foreach ($data as $row) {
            Mahadasha::create([
                'king_planet_id'   => (int) $row['kingNo'],
                'message'    => $row['message'],
            ]);
        }
    }
}
