<?php

namespace Database\Seeders;

use App\Models\Planet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $planets = [
            ['king_no' => 1, 'name' => 'Sun'],
            ['king_no' => 2, 'name' => 'Moon'],
            ['king_no' => 3, 'name' => 'Jupiter'],
            ['king_no' => 4, 'name' => 'Rahu'],
            ['king_no' => 5, 'name' => 'Mercury'],
            ['king_no' => 6, 'name' => 'Venus'],
            ['king_no' => 7, 'name' => 'Ketu'],
            ['king_no' => 8, 'name' => 'Shani'],
            ['king_no' => 9, 'name' => 'Mangal (Mars)'],
        ];

        foreach ($planets as $planet) {
            Planet::updateOrCreate(
                ['king_no' => $planet['king_no']],
                $planet
            );
        }
    }
}
