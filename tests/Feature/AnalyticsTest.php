<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Bin;
use App\Models\Alert;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_bin_summary_endpoint()
    {
        Bin::factory()->count(3)->create(['level' => 'empty']);
        Bin::factory()->count(2)->create(['level' => 'full']);

        $binWithAlert = Bin::factory()->create(['level' => 'partial']);
        Alert::factory()->create(['bin_id' => $binWithAlert->id]);

        $response = $this->getJson('/api/admin/analytics/bins/summary');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_bins',
                'bins_by_level',
                'bins_with_alerts',
                'bins_without_alerts'
            ])
            ->assertJson([
                'total_bins' => 6,
                'bins_by_level' => [
                    'empty' => 3,
                    'full' => 2,
                    'partial' => 1,
                ],
                'bins_with_alerts' => 1,
                'bins_without_alerts' => 5,
            ]);
    }

    public function test_alert_stats_endpoint()
    {
        Alert::factory()->count(5)->create(['type' => 'level_change', 'status' => 'open']);
        Alert::factory()->count(3)->create(['type' => 'public_report', 'status' => 'closed']);

        $response = $this->getJson('/api/admin/analytics/alerts/stats');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'total_alerts',
                'alerts_by_type',
                'alerts_by_status',
                'average_resolution_time_hours'
            ])
            ->assertJson([
                'total_alerts' => 8,
                'alerts_by_type' => [
                    'level_change' => 5,
                    'public_report' => 3,
                ],
                'alerts_by_status' => [
                    'open' => 5,
                    'closed' => 3,
                ],
            ]);
    }

    public function test_collection_efficiency_endpoint()
    {
        $bin1 = Bin::factory()->create();
        $bin2 = Bin::factory()->create();

        // Bin 1: 4 alerts, 2 collections
        Alert::factory()->count(2)->create(['bin_id' => $bin1->id, 'type' => 'level_change']);
        Alert::factory()->count(2)->create(['bin_id' => $bin1->id, 'type' => 'collector_action']);

        // Bin 2: 2 alerts, 1 collection
        Alert::factory()->create(['bin_id' => $bin2->id, 'type' => 'level_change']);
        Alert::factory()->create(['bin_id' => $bin2->id, 'type' => 'collector_action']);

        $response = $this->getJson('/api/admin/analytics/collection/efficiency');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'overall_efficiency',
                'bin_collection_rates'
            ])
            ->assertJson([
                'overall_efficiency' => 50.0, // 3 collections out of 6 alerts
            ]);

        $this->assertCount(2, $response->json('bin_collection_rates'));
    }

    public function test_alert_stats_with_period_filter()
    {
        // Create old alerts
        Alert::factory()->count(2)->create(['created_at' => now()->subMonths(2)]);

        // Create recent alerts
        Alert::factory()->count(3)->create(['created_at' => now()->subDays(3)]);

        $response = $this->getJson('/api/admin/analytics/alerts/stats?period=week');

        $response->assertStatus(200)
            ->assertJson(['total_alerts' => 3]);
    }
}
