<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Seller;
use App\Models\SocialProfile;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserDataSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user with all related data
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user->assignRole('admin');

        // Create social profiles for the user
        $socialProfiles = SocialProfile::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        // Create an address for the user
        Address::factory()->create([
            'user_id' => $user->id,
        ]);

        // Create a seller for the user with the first social profile
        Seller::factory()->create([
            'user_id' => $user->id,
            'social_profile_id' => $socialProfiles->first()->id,
        ]);

        // Create additional users with random data
        $rolePattern = ['user', 'user', 'user', 'user', 'user', 'seller', 'support'];

        User::factory()->count(30)->create()
            ->each(function (User $user, int $index) use ($rolePattern) {
                if ($index % 3 === 0) {
                    $user->forceFill(['email_verified_at' => null])->save();
                }

                $user->assignRole($rolePattern[$index % count($rolePattern)]);

                // Create 1-3 social profiles per user
                $socialProfiles = SocialProfile::factory()
                    ->count(rand(1, 3))
                    ->create(['user_id' => $user->id]);

                // Create an address
                Address::factory()->create(['user_id' => $user->id]);

                // Create a seller with a random social profile
                Seller::factory()->create([
                    'user_id' => $user->id,
                    'social_profile_id' => $socialProfiles->random()->id,
                ]);
            });
    }
}
