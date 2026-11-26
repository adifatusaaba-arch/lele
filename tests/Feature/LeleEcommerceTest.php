<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeleEcommerceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_storefront_pages_load_successfully(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('LELE');
        $this->get('/produk')->assertStatus(200);

        $product = Product::first();
        $this->get('/produk/' . $product->slug)->assertStatus(200)->assertSee($product->name);
    }

    public function test_role_middleware_protects_admin_routes(): void
    {
        $customer = User::where('role', 'customer')->first();
        $admin = User::where('role', 'admin')->first();

        $this->actingAs($customer)
            ->get('/admin/dashboard')
            ->assertRedirect(route('home'));

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee('Dashboard Overview');
    }

    public function test_customer_can_add_to_cart_checkout_and_admin_can_verify_payment_with_stock_deduction(): void
    {
        $customer = User::where('email', 'budi@example.com')->first();
        $admin = User::where('role', 'admin')->first();
        $product = Product::where('slug', 'lele-konsumsi-segar-standar')->first();
        $initialStock = $product->stock;
        $address = $customer->addresses()->first();

        // Add to cart
        $this->actingAs($customer)
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 5,
            ])
            ->assertRedirect();

        $this->actingAs($customer)
            ->get(route('cart.index'))
            ->assertStatus(200)
            ->assertSee($product->name);

        // Checkout
        $response = $this->actingAs($customer)
            ->post(route('customer.checkout.store'), [
                'address_id' => $address->id,
                'shipping_method' => 'kurir_toko',
                'payment_method' => 'transfer_bca',
                'notes' => 'Test order',
            ]);

        $order = Order::where('user_id', $customer->id)->latest('id')->first();
        $response->assertRedirect(route('customer.orders.show', $order));
        $this->assertEquals('menunggu_pembayaran', $order->status);
        $this->assertFalse($order->stock_deducted);

        // Another customer cannot view this order
        $otherCustomer = User::where('email', 'siti@example.com')->first();
        $this->actingAs($otherCustomer)
            ->get(route('customer.orders.show', $order))
            ->assertStatus(403);

        // Admin verifies payment -> stock is deducted
        $order->payment->update(['status' => 'menunggu_verifikasi']);
        $this->actingAs($admin)
            ->patch(route('admin.payments.verify', $order->payment))
            ->assertRedirect();

        $order->refresh();
        $product->refresh();

        $this->assertEquals('dibayar', $order->status);
        $this->assertTrue($order->stock_deducted);
        $this->assertEquals($initialStock - 5, $product->stock);

        // Admin cancels order -> stock is restored
        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), ['status' => 'dibatalkan'])
            ->assertRedirect();

        $order->refresh();
        $product->refresh();

        $this->assertEquals('dibatalkan', $order->status);
        $this->assertFalse($order->stock_deducted);
        $this->assertEquals($initialStock, $product->stock);
    }

    public function test_admin_can_crud_category_and_access_reports_and_settings(): void
    {
        $admin = User::where('role', 'admin')->first();

        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Lele Olahan',
                'description' => 'Produk olahan lele',
                'sort_order' => 5,
                'is_active' => 1,
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', ['slug' => 'lele-olahan']);

        $this->actingAs($admin)->get(route('admin.reports.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.settings.index'))->assertStatus(200);
    }
}
