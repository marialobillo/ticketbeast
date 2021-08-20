<?php

namespace Tests\Feature;

use App\Models\Concert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTicketTest extends TestCase
{
    /**
     * @test
     */
    public function customer_can_purchase_concert_tickets()
    {
        // Arrange
        // Create a concert
        $concert = Concert::factory()->published()->create([
            'ticket_price' => 3250
        ]);

        //Act
        // Purchase concert tickets
        $this->json('POST', '/concerts/{$concert->id}/orders', [
            'email' => 'jane@mail.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidToken(),
        ]);

        // Assert
        // Make sure the customer was charged the correct amount
        $order = $concert->orders()->where('email', 'jane@mail.com')->first();
        $this->assertEquals(9750, $paymentGateway->totalCharges);
        // Make sure that an order exists for this customer
        $this->assertTrue($concert->orders->contains(function($order){
            return $order->email == 'jane@mail.com';
        }));
        $this->assertNotNull($order);
        $this->assertEquals(3, $order->tickets->count());
    }
}
