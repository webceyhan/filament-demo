<?php

namespace App\Enums;

use App\Traits\HasEnumAccessors;

enum ProductType: string
{
    use HasEnumAccessors;

    case Deliverable = 'deliverable';
    case Downloadable = 'downloadable';
}
