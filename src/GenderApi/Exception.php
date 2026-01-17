<?php

declare(strict_types=1);

namespace GenderApi;

/**
 * Base exception class for GenderApi
 */
class Exception extends \Exception
{
    public function getErrorNo(): int
    {
        return $this->getCode();
    }
}