<?php

namespace App\Enums;

use App\Traits\HasEnumAccessors;

enum OrderStatus: string
{
    use HasEnumAccessors;

    case Pending = 'pending';
    case Processing = 'processing';
    case Completed = 'completed';
    case Declined = 'declined';

    public function color(): string
    {
        return match ($this->value) {
            'pending' => 'gray',
            'processing' => 'warning',
            'completed' => 'success',
            'declined' => 'danger',
        };
    }
}
