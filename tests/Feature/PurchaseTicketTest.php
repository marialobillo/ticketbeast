<?php

namespace Tests\Feature;

use App\Billing\FakePaymentGateway;
use App\Billing\PaymentGateway;
use App\Models\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTicketTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;
    /**
     * @test
     */
    public function customer_can_purchase_concert_tickets()
    {
        $paymentGateway = new FakePaymentGateway;
        $this->app->instance(PaymentGateway::class, $paymentGateway);
        // Arrange
        // Create a concert
        $concert = Concert::factory()->published()->create([
            'ticket_price' => 3250,
        ]);

        //Act
        // Purchase concert tickets
        $response = $this->json('POST', '/concerts/{$concert->id}/orders', [
            'email' => 'jane@mail.com',
            'ticket_quantity' => 3,
            'payment_token' => $paymentGateway->getValidTestToken(),
        ]);

        // Assert
        $response->assertStatus(201);
        // Make sure the customer was charged the correct amount
        $order = $concert->orders()->where('email', 'jane@mail.com')->first();
        $response->assertEquals(9750, $paymentGateway->totalCharges);
        // Make sure that an order exists for this customer
        $this->assertTrue($concert->orders->contains(function($order){
            return $order->email == 'jane@mail.com';
        }));
        $response->assertNotNull($order);
        $response->assertEquals(3, $order->tickets->count());
    }
}
