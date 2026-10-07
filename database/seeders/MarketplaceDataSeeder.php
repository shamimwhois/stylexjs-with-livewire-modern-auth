<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Changelog;
use App\Models\FeatureRequest;
use App\Models\Licence;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketplaceDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::each(function (User $user) {
            // Every user gets exactly one wallet
            Wallet::factory()->create([
                'user_id' => $user->id,
            ]);

            // Each user owns one or two licences
            Licence::factory()->count(rand(1, 2))->create([
                'user_id' => $user->id,
            ]);

            // Each user submits a feature request
            FeatureRequest::factory()->create([
                'user_id' => $user->id,
            ]);
        });

        // Standalone records
        Changelog::factory()->count(5)->create();
        Addon::factory()->count(5)->create();
    }
}
