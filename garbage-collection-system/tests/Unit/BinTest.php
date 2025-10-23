<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Bin;

class BinTest extends TestCase
{
    public function test_bin_can_be_created()
    {
        $bin = Bin::create([
            'location' => 'Trinidad, Bohol',
            'status' => 'empty',
        ]);

        $this->assertDatabaseHas('bins', [
            'location' => 'Trinidad, Bohol',
            'status' => 'empty',
        ]);
    }

    public function test_bin_status_can_be_updated()
    {
        $bin = Bin::create([
            'location' => 'Trinidad, Bohol',
            'status' => 'empty',
        ]);

        $bin->status = 'full';
        $bin->save();

        $this->assertEquals('full', $bin->status);
    }

    public function test_bin_status_can_be_checked()
    {
        $bin = Bin::create([
            'location' => 'Trinidad, Bohol',
            'status' => 'overflowing',
        ]);

        $this->assertTrue($bin->isOverflowing());
        $this->assertFalse($bin->isEmpty());
        $this->assertFalse($bin->isFull());
    }
}