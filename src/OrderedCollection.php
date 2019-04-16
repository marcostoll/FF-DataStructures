<?php
/**
 * Definition of OrderedCollection
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\DataStructures;

/**
 * Class OrderedCollection
 *
 * Represents an ordered list structure of consecutive indexed items beginning with 0.
 * Stores multiple (or zero) items that typically share a common data type or ancestor.
 *
 * The collection's items may be accessed via index. New Items may be appended or prepended.
 * Stack operations may be performed.
 *
 * @package FF\DataStructures
 */
class OrderedCollection extends IndexedCollection
{
    /**
     * {@inheritdoc}
     *
     * Enforces a consecutive numeric indexing (beginning with 0) of the given $items array.
     */
    public function setItems(array $items)
    {
        $items = array_values($items);
        return parent::setItems($items);
    }

    /**
     * {@inheritdoc}
     *
     * @param int $offset
     * @throws \InvalidArgumentException accepts only integers as $offset
     */
    public function has($offset): bool
    {
        if (!is_int($offset)) {
            throw new \InvalidArgumentException('accepts only integers as $offset');
        }

        return parent::has($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @param int $offset
     * @throws \InvalidArgumentException accepts only integers as $offset
     */
    public function get($offset)
    {
        if (!is_int($offset)) {
            throw new \InvalidArgumentException('accepts only integers as $offset');
        }

        return parent::get($offset);
    }

    /**
     * {@inheritdoc}
     *
     * If $offset exceeds the length of the current collection, the item will be appended to the end of the collection
     * instead.
     *
     * @param int $offset a positive integer
     * @throws \InvalidArgumentException accepts only integers as $offset
     * @throws \OutOfBoundsException only non-negative offsets are allowed
     */
    public function set($offset, $item): parent
    {
        if (!is_int($offset)) {
            throw new \InvalidArgumentException('accepts only integers as $offset');
        }
        if ($offset < 0) {
            throw new \OutOfBoundsException('only non-negative offsets are allowed, [' . $offset . '] is negative');
        }

        // check for null items
        if (is_null($item)) return $this->unset($offset);

        if ($this->has($offset)) {
            // replace
            $this->items[$offset] = $item;
            return $this;
        } else {
            // append
            return $this->push($item);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param int $offset
     * @throws \InvalidArgumentException accepts only integers as $offset
     */
    public function unset($offset)
    {
        if (!is_int($offset)) {
            throw new \InvalidArgumentException('accepts only integers as $offset');
        }

        parent::unset($offset);

        $this->setItems(array_values($this->getItems()));
        return $this;
    }

    /**
     * Retrieves the first item of the collection (if any)
     *
     * @return mixed|null
     */
    public function getFirst()
    {
        return $this->get(0);
    }

    /**
     * Retrieves the last item of the collection (if any)
     *
     * @return mixed|null
     */
    public function getLast()
    {
        return $this->get(count($this) - 1);
    }

    /**
     * Truncates the collection to the given length
     *
     * @param int $length a non-negative integer
     * @return $this
     * @throws \OutOfBoundsException only non-negative lengths are allowed
     */
    public function truncate(int $length)
    {
        if ($length < 0) {
            throw new \OutOfBoundsException('only non-negative lengths are allowed, [' . $length . '] is negative');
        }

        $this->items = array_slice($this->items, 0, $length);
        return $this;
    }

    /**
     * Appends the items of a given array or collection to the end of this collection
     *
     * @param array|Collection $collection
     * @return $this
     * @throws \InvalidArgumentException array or Collection expected for $collection
     */
    public function append($collection)
    {
        if ($collection instanceof Collection) {
            $newItems = array_values($collection->getItems());
        } elseif (is_array($collection)) {
            $newItems = array_values($collection);
        } else {
            throw new \InvalidArgumentException('array or ' . Collection::class . ' expected for $collection');
        }

        $this->items = array_merge($this->items, $newItems);
        return $this;
    }

    /**
     * Sorts the items of the collection using the given callback
     *
     * @param callable $callback
     * @return $this
     */
    public function sort(callable $callback)
    {
        usort($this->items, $callback);
        return $this;
    }

    // <editor-fold defaultstate="collapsed" desc="[ stack operations ]">

    /**
     * Appends one or more new items to the end of the collection
     *
     * @param mixed $items
     * @return $this
     */
    public function push(...$items)
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }

        return $this;
    }

    /**
     * Prepends an item to the beginning of the collection
     *
     * Note that the list of items is prepended as a whole, so that the prepended elements stay in the same order.
     *
     * @param mixed $items
     * @return $this
     */
    public function unshift(...$items)
    {
        array_unshift($this->items, ...$items);
        return $this;
    }

    /**
     * Pops the last item of the collection
     *
     * Returns null on an empty collections
     *
     * @return mixed|null
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Shifts the first item of the collection
     *
     * Returns null on an empty collections
     *
     * @return mixed|null
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    // </editor-fold>
}