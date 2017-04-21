<?php

namespace GenderApi\Client\Result;

use GenderApi\Client\RuntimeException;

/**
 * Class MultipleNames
 * @package GenderApi\Client\Result
 */
class MultipleNames extends AbstractResult implements \Iterator, \Countable
{

    /**
     * @var SingleName[]
     */
    private $names = array();

    /**
     * @var int
     */
    private $position = 0;

    /**
     * MultipleNames constructor.
     */
    public function __construct()
    {
        $this->position = 0;
    }

    /**
     *
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return SingleName
     */
    public function current()
    {
        return $this->names[$this->position];
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     *
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->names[$this->position]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->names);
    }

    /**
     * @param \stdClass $response
     */
    public function parseResponse(\stdClass $response)
    {
        $result = array();

        if (isset($response->result) && is_array($response->result)) {
            foreach ($response->result as $name) {

                if (isset($response->country)) {
                    $name->country = $response->country;
                }

                $entry = new SingleName();
                $entry->parseResponse($name);
                $result[] = $entry;
            }
        } else {
            throw new RuntimeException('Result not set');
        }

        $this->names = $result;
    }

}