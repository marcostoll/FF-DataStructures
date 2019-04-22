<?php
/**
 * Definition of IndexedCollection
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\DataStructures;

/**
 * Class IndexedCollection
 *
 * Represents an indexed list structure.
 * Stores multiple (or zero) items that typically share a common data type or ancestor.
 *
 * The collection's items may be accessed via index.
 *
 * @package FF\DataStructures
 */
class IndexedCollection extends Collection implements \ArrayAccess
{
    /**
     * Checks of a non-null value is present at the given index
     *
     * @param int|string $index
     * @return bool
     */
    public function has($index): bool
    {
        return isset($this->items[$index]);
    }

    /**
     * Retrieves the item at the given index or the $default value if $index is not present
     *
     * @param int|string $index
     * @param mixed $default
     * @return mixed
     */
    public function get($index, $default = null)
    {
        return $this->items[$index] ?? $default;
    }

    /**
     * Replaces the item at the given index
     *
     * Adds a new item to the collection, if $index was not yet present.
     * If $item is null and $offset points to an existing item, the existing item will be unset instead.
     *
     * @param int|string $index
     * @param mixed $item
     * @return $this
     */
    public function set($index, $item)
    {
        // check for null items
        if (is_null($item)) return $this->unset($index);

        $this->items[$index] = $item;
        return $this;
    }

    /**
     * Removes the item at the given offset (if exists)
     *
     * @param int|string $index
     * @return $this
     */
    public function unset($index)
    {
        unset($this->items[$index]);
        return $this;
    }

    /**
     * Retrieves the list of indexes used for all the items in the collection
     *
     * @return array
     */
    public function getKeys(): array
    {
        return array_keys($this->items);
    }

    /**
     * Retrieves the first index of the given $item if present
     *
     * Returns null if $item is not present.
     * If $strict is set, $items will be compared in types and object instances as well
     *
     * @param mixed $item
     * @param bool $strict
     * @return int|string|null
     */
    public function search($item, bool $strict = false)
    {
        $result = array_search($item, $this->items, $strict);
        return ($result !== false) ? $result : null;
    }

    // <editor-fold defaultstate="collapsed" desc="[ \ArrayAccess ]">

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        return $this->unset($offset);
    }

    // </editor-fold>
}