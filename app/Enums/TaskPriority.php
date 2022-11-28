<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskPriority extends Enum
{

    const LOW = 'low';
    const MEDIUM = 'medium';
    const HIGH = 'high';
    const VERY_HIGH = 'very high';

}
