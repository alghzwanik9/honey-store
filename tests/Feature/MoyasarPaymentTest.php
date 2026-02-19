<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoyasarPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_process_and_callback_simulation()
    {
        // 1. Arrange: Create User, Category, Product
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Honey', 'slug' => 'honey']);
        $product = Product::create([
            'name' => 'Sidr Honey',
            'slug' => 'sidr-honey',
            'description' => 'Best honey',
            'price' => 100,
            'stock' => 10,
            'is_active' => true,
            'category_id' => $category->id,
        ]);

        // 2. Simulate Cart in Session
        $cart = [
            $product->id => [
                "name" => $product->name,
                "quantity" => 2,
                "price" => $product->price,
                "image" => "image.jpg"
            ]
        ];

        // 3. Act: Submit Checkout Form to create Order
        $response = $this->actingAs($user)
            ->withSession(['cart' => $cart])
            ->post(route('checkout.store'), [
                'address' => 'Riyadh, Street 1',
                'city' => 'Riyadh',
                'postal_code' => '12345',
                'payment_method' => 'card',
                'latitude' => 24.7136,
                'longitude' => 46.6753,
            ]);

        // Assert redirect to payment or bnpl page. In this case 'card' -> checkout.payment
        $response->assertRedirect();

        // Get the created order
        $order = Order::latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals('pending', $order->payment_status);
        $this->assertEquals($product->price * 2, $order->total_amount);

        // 4. Act: Simulate Payment Callback (Success)
        // Using the "simulated_" prefix logic in PaymentController@callback
        $callbackResponse = $this->actingAs($user)
            ->withSession(['cart' => $cart]) // Ensure cart is still there before callback clears it
            ->get(route('checkout.callback', [
                'id' => 'simulated_payment_123',
                'status' => 'paid',
                'message' => 'APPROVED',
                'order_id' => $order->id
            ]));

        // 5. Assert: 
        // - Redirects to success page
        $callbackResponse->assertRedirect(route('checkout.success', $order->id));

        // - Order updated in DB
        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('processing', $order->status);
        $this->assertEquals('simulated_payment_123', $order->payment_id);

        // - Cart cleared from session
        $callbackResponse->assertSessionMissing('cart');
    }
}
