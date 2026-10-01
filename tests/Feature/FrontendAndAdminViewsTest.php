<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class FrontendAndAdminViewsTest extends TestCase
{
    public function test_customer_pages_render_successfully()
    {
        $order = Order::first();

        $this->get('/')->assertStatus(200);
        $this->get('/menu')->assertStatus(200);
        $this->get('/cart')->assertStatus(200);
        $this->get('/checkout')->assertStatus(200);
        $this->get('/lacak-pesanan')->assertStatus(200);

        if ($order) {
            $this->get("/pesanan-berhasil/{$order->order_code}")->assertStatus(200);
            $this->get("/struk/{$order->order_code}")->assertStatus(200);
            $this->get("/lacak-pesanan?query={$order->order_code}")->assertStatus(200);
        }
    }

    public function test_admin_dashboard_and_views_render_successfully()
    {
        $admin = User::where('role', 'admin')->first();
        $order = Order::first();

        $this->get('/admin/login')->assertStatus(200);

        $this->actingAs($admin)->get('/admin')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/orders')->assertStatus(200);
        if ($order) {
            $this->actingAs($admin)->get("/admin/orders/{$order->id}")->assertStatus(200);
            $this->actingAs($admin)->get("/admin/orders/{$order->id}/receipt")->assertStatus(200);
        }
        $this->actingAs($admin)->get('/admin/products')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/products/create')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/categories')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/reports')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/settings')->assertStatus(200);
    }
}
