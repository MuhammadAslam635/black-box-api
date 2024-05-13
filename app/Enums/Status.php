<?php

namespace App\Enums;

enum Status: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case BLOCK = 'block';
    case DECLINED = 'declined';
    case DELETE = 'delete';
}
