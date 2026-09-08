<?php

namespace Workbench\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Workbench\App\Models\Client;
use Workbench\App\Models\Project;
use Workbench\Database\Factories\ClientFactory;
use Workbench\Database\Factories\UserFactory;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the panel with enough invented work to photograph.
     *
     * Seeded from a fixed number, so the same records come out in the same
     * order every time. Screenshots of a panel whose contents reshuffle on
     * every build are unusable as a before-and-after.
     */
    public function run(): void
    {
        fake()->seed(20260907);

        $account = UserFactory::new()->create([
            'name' => 'Valeria Ossa',
            'email' => 'valeria@mia.test',
            'password' => 'password',
        ]);

        $team = UserFactory::new()->createMany([
            ['name' => 'Rafael Iturbe'],
            ['name' => 'Noor Haddad'],
            ['name' => 'Anders Vik'],
            ['name' => 'Priya Raman'],
        ]);

        $owners = $team->push($account);

        foreach (ClientFactory::RECORDS as [$name, $industry, $city, $tier]) {
            Client::factory()->create([
                'name' => $name,
                'industry' => $industry,
                'city' => $city,
                'tier' => $tier,
            ]);
        }

        $clients = Client::query()->where('tier', '!=', 'prospect')->get();

        // Stages and health cycle rather than land at random, so every badge
        // the panel can draw is on screen in the first page of the list.
        $stages = ['discovery', 'design', 'build', 'review', 'on_hold'];
        $health = ['on_track', 'at_risk', 'on_track', 'blocked'];

        Project::factory()
            ->count(14)
            ->sequence(fn ($sequence): array => [
                'client_id' => $clients[$sequence->index % $clients->count()]->id,
                'owner_id' => $owners[$sequence->index % $owners->count()]->id,
                'status' => $stages[$sequence->index % count($stages)],
                'health' => $health[$sequence->index % count($health)],
            ])
            ->create();

        Project::factory()
            ->count(4)
            ->delivered()
            ->sequence(fn ($sequence): array => [
                'client_id' => $clients[$sequence->index % $clients->count()]->id,
                'owner_id' => $owners[$sequence->index % $owners->count()]->id,
            ])
            ->create();
    }
}
