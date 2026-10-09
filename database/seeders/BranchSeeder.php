<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $branches = [
            [
                'code' => 'LON',
                'name' => 'London Office',
                'address' => 'Suite 3, 2nd Floor, LMC Business Wing, 38-44 Whitechapel Road',
                'city' => 'London',
                'country' => 'United Kingdom',
                'phone' => '+44 7402 993321',
                'email' => 'info@gconsultancy.co.uk',
            ],
            [
                'code' => 'KHU',
                'name' => 'Khulna Office',
                'address' => 'House 52, Alingon House, Road 5, Nirala R/A',
                'city' => 'Khulna',
                'country' => 'Bangladesh',
                'phone' => '+880 1744 742686',
                'email' => 'info@gconsultancy.co.uk',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(['code' => $branch['code']], $branch);
        }
    }
}
