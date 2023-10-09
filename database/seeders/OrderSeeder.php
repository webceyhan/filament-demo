<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create 0-2 orders for each customer
        \App\Models\Customer::all()->each(function ($customer) {
            \App\Models\Order::factory(rand(0, 2))->create([
                'customer_id' => $customer->id,
            ]);
        });

        // add some products to each order
        \App\Models\Order::all()->each(function ($order) {
            $products = \App\Models\Product::inRandomOrder()->take(3)->get();

            $products->each(function ($product) use ($order) {
                $order->items()->save(new \App\Models\OrderItem([
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                    'unit_price' => $product->price,
                ]));
                
                // update the order's total price
                $order->total_price += $product->price;
            });
        });
    }
}
