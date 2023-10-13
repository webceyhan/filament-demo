<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return collect(OrderStatus::values())
            ->mapWithKeys(
                function (string $status) {
                    return [
                        $status => Tab::make()
                            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status))
                            ->badge(Order::where('status', $status)->count())
                            ->badgeColor(OrderStatus::from($status)->color())

                    ];
                }
            )
            ->prepend(Tab::make()->label('All'), 'all')
            ->toArray();
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return OrderStatus::Processing->value;
    }
}
