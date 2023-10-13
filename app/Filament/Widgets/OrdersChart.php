<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();


        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => array_values($data)                    
                ],
                'labels' => OrderStatus::cases()
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
