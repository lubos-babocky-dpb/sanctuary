<?php
declare(strict_types=1);
namespace Dpb\Sanctuary\Exceptions;

use RuntimeException;

class SanctuaryAuthenticatableNotSupportedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct(
            message: 'The configured Sanctuary authenticatable does not implement SanctuaryAuthenticatable.'
        );
    }
}