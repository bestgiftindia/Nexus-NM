<?php

namespace Database\Seeders;

use App\Models\LuckyUnluckyNumber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LuckyUnluckySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = json_decode(file_get_contents(database_path('seeders/data/lucky_unlucky_number_table.json')), true);

        foreach ($data as $row) {
            LuckyUnluckyNumber::create([
                'king_planet_id'   => $row['king'],
                'queen_planet_id'  => $row['queen'],

                'lucky_numbers'    => $row['balancer']
                    ? explode(',', $row['balancer'])
                    : null,

                'neutral_number'   => $row['neutral_number']
                    ? explode(',', $row['neutral_number'])
                    : null,

                'unlucky_numbers'  => $row['unlucky_number']
                    ? explode(',', $row['unlucky_number'])
                    : null,

                'lucky_color'      => $row['lucky_colour']
                    ? explode(',', $row['lucky_colour'])
                    : null,

                'unlucky_color'    => $row['unlucky_colour']
                    ? explode(',', $row['unlucky_colour'])
                    : null,
            ]);
        }
    }
}
