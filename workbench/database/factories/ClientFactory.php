<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Client;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Invented studios, invented trades.
     *
     * Written out rather than generated so a screenshot taken today matches
     * one taken next month: faker would reshuffle the names on every seed and
     * every capture would be a new diff.
     *
     * @var array<int, array{0: string, 1: string, 2: string, 3: string}>
     */
    public const RECORDS = [
        ['Northbank Ceramics', 'Manufacturing', 'Leeds', 'retainer'],
        ['Halden Freight', 'Logistics', 'Rotterdam', 'retainer'],
        ['Marlow & Vane', 'Architecture', 'Bristol', 'retainer'],
        ['Verdemar Foods', 'Food and drink', 'Porto', 'project'],
        ['Ashgrove Vineyards', 'Agriculture', 'Adelaide', 'project'],
        ['Petronella Press', 'Publishing', 'Antwerp', 'project'],
        ['Quill & Ledger', 'Professional services', 'Edinburgh', 'project'],
        ['Sunderby Clinics', 'Healthcare', 'Malmö', 'project'],
        ['Kestrel Outdoors', 'Retail', 'Boulder', 'project'],
        ['Lumen Housing', 'Construction', 'Dublin', 'prospect'],
        ['Tidewater Marine', 'Marine services', 'Halifax', 'prospect'],
        ['Fairhaven Trust', 'Non-profit', 'Wellington', 'prospect'],
    ];

    public function definition(): array
    {
        [$name, $industry, $city, $tier] = fake()->randomElement(self::RECORDS);

        return [
            'name' => $name,
            'industry' => $industry,
            'city' => $city,
            'tier' => $tier,
        ];
    }
}
