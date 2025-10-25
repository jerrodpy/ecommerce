<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Models\Order;
use App\Models\OrderProductPivot;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            Order::COLUMN_CUSTOMER_FIO => $this->faker->name(),
            Order::COLUMN_CUSTOMER_PHONE => $this->faker->phoneNumber(),
            Order::COLUMN_COMMENTS => $this->faker->optional(0.7)->sentence(),
            Order::COLUMN_STATUS => $this->faker->randomElement(Status::cases()),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function ($order) {
            $products = Product::inRandomOrder()
                ->limit(rand(1, 5))
                ->get();

            foreach ($products as $product) {
                $order->products()->attach($product->id, [
                    OrderProductPivot::COLUMN_QUANTITY => rand(1, 5),
                    OrderProductPivot::COLUMN_PRICE => $product->{Product::COLUMN_PRICE},
                ]);
            }
        });
    }

    public function withProducts(int $count = 3): static
    {
        return $this->afterCreating(function ($order) use ($count) {
            $products = Product::inRandomOrder()
                ->limit($count)
                ->get();

            foreach ($products as $product) {
                $order->products()->attach($product->id, [
                    OrderProductPivot::COLUMN_QUANTITY => rand(1, 5),
                    OrderProductPivot::COLUMN_PRICE => $product->{Product::COLUMN_PRICE},
                ]);
            }
        });
    }
}
