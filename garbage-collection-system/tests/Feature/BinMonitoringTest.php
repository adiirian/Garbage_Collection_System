<?php

namespace Tests\Feature;

use App\Models\Bin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BinMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_bin_status_full_alert()
    {
        Notification::fake();

        $bin = Bin::create(['status' => 'full', 'location' => 'Trinidad, Bohol']);
        $collector = User::factory()->create(['role_id' => 2]); // Assuming role_id 2 is for collectors

        $this->actingAs($collector)
            ->post('/api/bins/' . $bin->id . '/status', ['status' => 'full']);

        Notification::assertSentTo($collector, \App\Notifications\BinFullNotification::class);
    }

    public function test_bin_status_overflow_alert()
    {
        Notification::fake();

        $bin = Bin::create(['status' => 'overflow', 'location' => 'Trinidad, Bohol']);
        $collector = User::factory()->create(['role_id' => 2]);

        $this->actingAs($collector)
            ->post('/api/bins/' . $bin->id . '/status', ['status' => 'overflow']);

        Notification::assertSentTo($collector, \App\Notifications\BinOverflowNotification::class);
    }

    public function test_public_user_can_send_alert()
    {
        $publicUser = User::factory()->create(['role_id' => 3]); // Assuming role_id 3 is for public users
        $bin = Bin::create(['status' => 'empty', 'location' => 'Trinidad, Bohol']);

        $response = $this->actingAs($publicUser)
            ->post('/api/alerts', [
                'bin_id' => $bin->id,
                'message' => 'The bin is full!'
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('alerts', [
            'bin_id' => $bin->id,
            'message' => 'The bin is full!'
        ]);
    }
}