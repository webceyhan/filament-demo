<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        [$products, $months] = $this->getProductsPerMonth();

        return [
            'datasets' => [
                [
                    'label' => 'Total Transactions',
                    'data' => $products,
                    // 'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    // 'borderColor' => 'rgb(255, 99, 132)',
                    // 'borderWidth' => 1,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getProductsPerMonth(): array
    {
        $now = now();
        $products = [];

        $months = collect(range(1, 12))->map(function ($monthNumber) use ($now, &$products) {
            // get the month datetime object
            $month = $now->copy()->month($monthNumber);

            // get the count of products created in that year/month
            $products[] = Product::query()
                ->whereYear('published_at', $month->format('Y'))
                ->whereMonth('published_at', $month->format('m'))
                ->count();

            // return name of month
            return $month->format('M');
        });

        return [
            $products,
            $months,
        ];
    }
}