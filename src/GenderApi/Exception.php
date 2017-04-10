<?php

namespace GenderApi;

/**
 * Class Exception
 * @package GenderApi
 */
class Exception extends \Exception
{

    /**
     * @return int
     */
    public function getErrorNo()
    {
        return $this->getCode();
    }

}