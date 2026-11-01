<?php

declare(strict_types=1);

namespace App\Enums;

enum TenantStatus: string
{
    case Provisioning = 'provisioning';
    case Ready = 'ready';
    case Failed = 'failed';
}
