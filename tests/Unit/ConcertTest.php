<?php

namespace Tests\Unit;

use App\Models\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ConcertTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /**
     * @test
     */
    public function get_formatted_date()
    {
        $concert = Concert::factory()->make([
            'date' => Carbon::parse('2016-12-01 8:00pm'),
        ]);

        $date = $concert->formatted_date;

        $this->assertEquals('December 1, 2016',  $date);
    }

    /** @test */
    public function get_formatted_start_time()
    {
        $concert = Concert::factory()->make([
            'date' => Carbon::parse('2016-12-01 17:00:00'),
        ]);

        $this->assertEquals('5:00pm', $concert->formatted_start_time);
    }

    /** @test */
    public function get_ticket_price_in_dollars()
    {
        $concert = Concert::factory()->make([
            'ticket_price' => 6750,
        ]);

        $this->assertEquals('67.50', $concert->ticket_price_in_dollars);
    }
}
