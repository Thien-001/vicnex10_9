<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Product;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $products = Product::all();

        foreach ($orders as $order) {
            $product = $products->random();

            OrderDetail::create([
                'order_id'      => $order->order_id,
                'Product_ID'    => $product->Product_ID,
                'product_name'  => $product->Name,
                'SKU'           => 'SP-' . $product->Product_ID,
                'price'         => $product->Price,
                'quantity'      => rand(1, 3),
                'total'         => $product->Price * rand(1, 3),
                'create_at'     => now(),
            ]);
        }
    }
}
