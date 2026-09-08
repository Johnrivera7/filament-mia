<?php

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Workbench\App\Models\Client;
use Workbench\App\Models\Project;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Titles that read like real work without naming any.
     *
     * @var array<int, string>
     */
    public const TITLES = [
        'Rebuild the checkout flow',
        'Migrate billing to the new payments API',
        'Design system for the customer portal',
        'Warehouse availability across three sites',
        'Saved searches in the catalogue',
        'Single sign-on for corporate accounts',
        'Onboarding wizard for new workspaces',
        'Attachment handling in supplier records',
        'Quarterly reporting pack',
        'Accessibility pass on the booking journey',
        'Data export in the orders list',
        'Client detail view with full history',
        'Automated dunning for overdue invoices',
        'Photo library for the print catalogue',
        'Stock forecasting dashboard',
        'Contract renewals workflow',
        'Search relevance for long product names',
        'Offline mode for the field app',
    ];

    public function definition(): array
    {
        $budget = fake()->numberBetween(12, 96) * 1000;
        $progress = fake()->numberBetween(5, 100);
        $starts = fake()->dateTimeBetween('-8 months', '-2 weeks');

        return [
            'client_id' => Client::factory(),
            'code' => 'PR-' . fake()->unique()->numberBetween(1040, 1980),
            'name' => fake()->unique()->randomElement(self::TITLES),
            // No `delivered` here: that state carries a delivery date too, and
            // a row marked delivered without one reads as a bug in the panel.
            'status' => fake()->randomElement(['discovery', 'design', 'build', 'review', 'on_hold']),
            'health' => fake()->randomElement(['on_track', 'on_track', 'at_risk', 'blocked']),
            'priority' => fake()->randomElement(['low', 'normal', 'normal', 'high']),
            'progress' => $progress,
            'budget' => $budget,
            // Spend tracks progress loosely, so the two meters in the table
            // disagree the way they do on real work.
            'spent' => (int) round($budget * ($progress / 100) * fake()->randomFloat(2, 0.72, 1.24)),
            'starts_at' => $starts,
            'ends_at' => fake()->dateTimeBetween($starts, '+3 months'),
            'is_starred' => fake()->boolean(20),
            'brief' => fake()->randomElement([
                'Agreed with the client at the last sprint review. No change to the data model.',
                'Blocked on the third-party sandbox, which has been down since Thursday.',
                'Scope trimmed to the two screens that carry the traffic.',
                'Waiting on final copy before the build can be signed off.',
                'Follows the pattern set by the portal work earlier in the year.',
            ]),
        ];
    }

    public function delivered(): static
    {
        return $this->state(fn (): array => [
            'status' => 'delivered',
            'health' => 'on_track',
            'progress' => 100,
            'delivered_at' => fake()->dateTimeBetween('-5 months', '-3 days'),
        ]);
    }
}
