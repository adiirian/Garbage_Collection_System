<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Bin;
use App\Models\Alert;

class BinMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sensor_update_creates_alert()
    {
        $bin = Bin::factory()->create(['level' => 'partial']);

        $this->postJson("/api/admin/bins/{$bin->id}/overflowing")
            ->assertStatus(200)
            ->assertJsonFragment(['level' => 'overflowing']);

        $this->assertDatabaseHas('bins', [
            'id' => $bin->id,
            'level' => 'overflowing',
        ]);

        $this->assertDatabaseHas('alerts', [
            'bin_id' => $bin->id,
            'type' => 'level_change',
            'level' => 'overflowing',
            'status' => 'open',
        ]);
    }

    public function test_public_report_creates_alert()
    {
        $bin = Bin::factory()->create();

        $payload = ['bin_id' => $bin->id, 'message' => 'Public report test'];

        $this->postJson('/api/public/report', $payload)
            ->assertStatus(201)
            ->assertJsonFragment(['bin_id' => $bin->id, 'type' => 'public_report']);

        $this->assertDatabaseHas('alerts', [
            'bin_id' => $bin->id,
            'type' => 'public_report',
            'status' => 'open',
        ]);
    }

    public function test_collector_mark_cleaned_closes_alerts_and_sets_empty()
    {
        $bin = Bin::factory()->create(['level' => 'overflowing']);
        $alert = Alert::factory()->create([
            'bin_id' => $bin->id,
            'status' => 'open',
            'type' => 'level_change',
            'level' => 'overflowing',
        ]);

        $this->postJson("/api/collector/bins/{$bin->id}/clean")
            ->assertStatus(200)
            ->assertJsonFragment(['message' => 'Bin marked cleaned']);

        $this->assertDatabaseHas('bins', [
            'id' => $bin->id,
            'level' => 'empty',
        ]);

        $this->assertDatabaseHas('alerts', [
            'id' => $alert->id,
            'status' => 'closed',
            'type' => 'collector_action',
        ]);
    }
}
