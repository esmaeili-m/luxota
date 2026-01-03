<?php

namespace Modules\Referrer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Referrer\App\Models\Referrer;

class ReferrerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Referrer::Truncate();

        $referrers = [
            ['title' => 'Website', 'status' => true],
            ['title' => 'Social Media', 'status' => true],
            ['title' => 'Email Marketing', 'status' => true],
            ['title' => 'Direct Traffic', 'status' => true],
            ['title' => 'Organic Search', 'status' => true],
            ['title' => 'Paid Advertising', 'status' => true],
            ['title' => 'Referral Program', 'status' => true],
            ['title' => 'Partner Network', 'status' => true],
            ['title' => 'Mobile App', 'status' => true],
            ['title' => 'Word of Mouth', 'status' => true],
        ];

        foreach ($referrers as $referrer) {
            Referrer::create($referrer);
        }
    }
}
