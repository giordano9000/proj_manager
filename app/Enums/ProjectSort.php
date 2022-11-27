<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

final class ProjectSort extends Enum
{

    const ALPHA_DESC = 'alpha_desc';
    const ALPHA_ASC = 'alpha_asc';
    const CREATE = 'create';
    const UPDATE = 'update';

}
