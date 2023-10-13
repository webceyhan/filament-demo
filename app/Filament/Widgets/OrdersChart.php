<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Order::query()
            ->select('status')
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();


        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => array_values($data)
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
