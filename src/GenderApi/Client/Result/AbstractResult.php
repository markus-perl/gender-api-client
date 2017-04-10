<?php

namespace GenderApi\Client\Result;

/**
 * Class AbstractResult
 * @package GenderApi\Client\Result
 */
abstract class AbstractResult
{

    /**
     * @var null|string
     */
    protected $queryUrl = null;

    /**
     * @param \stdClass $response
     */
    abstract public function parseResponse(\stdClass $response);

    /**
     * @return string
     */
    public function __toString()
    {
        $result = PHP_EOL . get_class($this) . PHP_EOL;
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $name = $property->getName();
            $value = $this->$name;
            $result .= " " . $name . ': ' . $value . PHP_EOL;
        }

        return $result;
    }

    /**
     * @return null|string
     */
    public function getQueryUrl()
    {
        return $this->queryUrl;
    }

    /**
     * @param null|string $queryUrl
     */
    public function setQueryUrl($queryUrl)
    {
        $this->queryUrl = $queryUrl;
    }

}