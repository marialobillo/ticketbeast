<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewConcertListingTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_view_a_concert_listing()
    {
        // Arrange
        $concert = Concert::create([
            'title' => 'The Red Chord',
            'subtitle' => 'with Animosity and Lethargy',
            'date' => Carbon::parse('December 15, 2020 8:00pm'),
            'ticker_price' => 3250,
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Example Lane',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '17916',
            'additional_information' => 'For tickets, call (555) 555-5555.',
        ]);

        // Act
        $this->visit('/concerts/' . $concert->id);

        // Assert
        $this->see('The Red Chord');
        $this->see('with Animosity and Lethargy');
        $this->see('December 15, 2020 8:00pm');
        $this->see('32.50');
        $this->see('The Mosh Pit');
        $this->see('123 Example Lane');
        $this->see('Laraville');
        $this->see('ON');
        $this->see('17916');
        $this->see('For tickets, call (555) 555-5555.');

    }
}
