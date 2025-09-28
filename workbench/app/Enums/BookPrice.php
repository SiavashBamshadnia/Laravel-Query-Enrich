<?php

namespace Workbench\App\Enums;

/**
 * An example enum showing values in uppercase, lowercase, and mixed case.
 */
enum BookPrice: string
{
    case EXPENSIVE = 'expensive';

    case CHEAP = 'CHEAP';

    case Affordable = 'Affordable';
}
