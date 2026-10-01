<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderAndAdminBackendTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Seed needed base data if empty
        if (Category::count() === 0) {
            $this->artisan('db:seed');
        }
    }

    public function test_menu_api_returns_categories_and_products()
    {
        $response = $this->getJson('/api/v1/menu');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'categories' => [
                    '*' => ['id', 'name', 'slug', 'products']
                ]
            ]);
    }

    public function test_customer_can_checkout_order_with_items_and_shipping()
    {
        $product1 = Product::where('slug', 'nasi-biryani-ayam-rempah-khas')->first();
        $product2 = Product::where('slug', 'teh-tarik-rempah-kapulaga-dingin')->first();

        $payload = [
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567899',
            'order_type' => 'delivery',
            'table_or_address' => 'Jl. Tebet Barat No. 12, RT 05 RW 02, Jakarta Selatan',
            'payment_method' => 'qris',
            'notes' => 'Tolong sambal dipisah ya',
            'items' => [
                [
                    'id' => $product1->id,
                    'quantity' => 2,
                    'notes' => 'Paha ayam atas bawah',
                ],
                [
                    'id' => $product2->id,
                    'quantity' => 2,
                    'notes' => 'Kurang manis',
                ],
            ]
        ];

        $response = $this->postJson('/api/v1/checkout', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'order' => [
                    'id',
                    'order_code',
                    'total_amount',
                    'shipping_cost',
                    'items',
                ],
                'whatsapp_url',
            ]);

        $orderData = $response->json('order');
        $expectedTotal = (2 * $product1->price) + (2 * $product2->price) + 10000; // 10000 flat shipping
        $this->assertEquals($expectedTotal, $orderData['total_amount']);

        // Check database
        $this->assertDatabaseHas('orders', [
            'order_code' => $orderData['order_code'],
            'customer_name' => 'Budi Santoso',
            'order_type' => 'delivery',
            'order_status' => 'pending',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderData['id'],
            'product_name' => $product1->name,
            'quantity' => 2,
        ]);
    }

    public function test_customer_can_track_order_by_code()
    {
        $order = Order::latest()->first();

        $response = $this->getJson("/api/v1/orders/{$order->order_code}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'order' => [
                    'order_code' => $order->order_code,
                ]
            ]);
    }

    public function test_admin_can_login_with_correct_credentials()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@biryani.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_cannot_login_with_wrong_password()
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@biryani.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_view_dashboard_metrics()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->getJson('/admin/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'kpi' => [
                    'today_revenue',
                    'today_orders',
                    'pending_orders',
                    'processing_orders',
                    'completed_orders',
                ],
                'sales_trend',
                'top_products',
                'recent_orders',
            ]);
    }

    public function test_admin_can_update_order_status()
    {
        $admin = User::where('role', 'admin')->first();
        $order = Order::where('order_status', 'pending')->first();

        $response = $this->actingAs($admin)->patchJson("/admin/orders/{$order->id}/status", [
            'order_status' => 'processing',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_status' => 'processing',
        ]);
    }
}
