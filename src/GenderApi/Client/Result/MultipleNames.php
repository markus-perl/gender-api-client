<?php

declare(strict_types=1);

namespace GenderApi\Client\Result;

use Countable;
use Iterator;

/**
 * Result for multiple names gender lookup (API v2)
 *
 * @implements Iterator<int, SingleName>
 */
class MultipleNames extends AbstractResult implements Iterator, Countable
{
    /** @var array<int, SingleName> */
    private array $names = [];

    private int $position = 0;

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): SingleName
    {
        return $this->names[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->names[$this->position]);
    }

    public function count(): int
    {
        return count($this->names);
    }

    public function parseResponse(\stdClass $response): void
    {
        $result = [];

        // v2 API returns array of results directly (wrapped in 'results' by Query)
        $items = $response->results ?? [];

        if (!is_array($items)) {
            return;
        }

        foreach ($items as $item) {
            if (!$item instanceof \stdClass) {
                $item = (object) $item;
            }

            $entry = new SingleName();
            $entry->parseResponse($item);
            $result[] = $entry;
        }

        $this->names = $result;
    }
}