<?php
/**
 * Definition of Collection
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\DataStructures;

/**
 * Class Collection
 *
 * Represents a list structure.
 * Stores multiple (or zero) items that typically share a common data type or ancestor.
 *
 * The collection's items may be counted and/or traversed via foreach.
 *
 * @package FF\DataStructures
 * @see http://en.wikipedia.org/wiki/List_%28abstract_data_type%29
 */
class Collection implements \Countable, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Constructor
     *
     * @param array $items The items
     */
    public function __construct(array $items = [])
    {
        $this->setItems($items);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Removes all items from the collection
     *
     * @return $this
     */
    public function clear()
    {
        $this->items = [];
        return $this;
    }

    /**
     * Checks whether this collection is considered empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Retrieves the number of items stored within the collection
     *
     * @return int
     */
    public function getLength(): int
    {
        return count($this);
    }

    /**
     * Retrieves an array of values containing the results of applying the callback function to each item
     *
     * @param callable $callback
     * @return array
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->items);
    }

    /**
     * Applies the filter callback to all of the collection's items
     *
     * Only items that are considered true by the $callback will remain in the collection.
     *
     * @param callable $callback
     * @return $this
     */
    public function filter(callable $callback)
    {
        return $this->setItems(array_filter($this->getItems(), $callback));
    }

    // <editor-fold defaultstate="collapsed" desc="[ \Countable ]">

    /**
     * Retrieves the amount of items stored within the collection
     *
     * Implementation of the Countable interface
     *
     * @return int
     * @see \Countable
     */
    public function count(): int
    {
        return count($this->items);
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="[ \IteratorAggregate ]">

    /**
     * Retrieves an iterator to provide traversable support
     *
     * @return \ArrayIterator
     * @see \IteratorAggregate
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    // </editor-fold>
}
