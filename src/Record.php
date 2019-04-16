<?php
/**
 * Definition of Record
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\DataStructures;

use FF\Utilities\StringUtils;

/**
 * Class Record
 *
 * Represents a record data structure.
 * Contains an indexed collection of values and offers method's for
 * value retrieval and manipulation.
 * In addition to accessing value's by key (e.g. getField(), setField(), ...)
 * a magic __call() api is supported that mimics a default getter and setter api
 * for single field access.
 *
 * @package FF\Lib
 * @see __call()
 * @see http://en.wikipedia.org/wiki/Record_%28computer_science%29
 */
class Record implements \IteratorAggregate
{
    /**
     * @var IndexedCollection
     */
    protected $data;

    /**
     * Constructor
     *
     * iF $data is an array, it should be an associative array.
     *
     * @param array|IndexedCollection
     */
    public function __construct($data = [])
    {
        $this->setData($data);
    }

    /**
     * Sets the record's data
     *
     * If $data is an array, it should be an associative array.
     *
     * @param array|IndexedCollection $data
     * @return $this
     */
    public function setData($data)
    {
        if (is_array($data)) {
            $data = new IndexedCollection($data);
        }

        $this->data = $data;
        return $this;
    }

    /**
     * @return IndexedCollection
     */
    public function getData(): IndexedCollection
    {
        return $this->data;
    }

    /**
     * Retrieves the record's data as array
     *
     * @return array
     */
    public function getDataAsArray(): array
    {
        return $this->data->getItems();
    }

    /**
     * Retrieves a field value
     *
     * @param string $key
     * @return mixed|null
     */
    public function getField(string $key)
    {
        return $this->data->has($key) ? $this->data->get($key) : null;
    }

    /**
     * Sets a field value
     *
     * @param string $key
     * @param mixed|null $value
     * @return  $this
     */
    public function setField(string $key, $value)
    {
        $this->data->set($key, $value);
        return $this;
    }

    /**
     * Checks if a data field is present
     *
     * @param string $key
     * @return bool
     */
    public function hasField(string $key): bool
    {
        return $this->data->has($key);
    }

    /**
     * Unsets a data field
     *
     * @param string $key
     * @return $this
     */
    public function unsetField(string $key)
    {
        $this->data->unset($key);
        return $this;
    }

    /**
     * Removes all data from the record
     *
     * @return $this
     */
    public function clear()
    {
        $this->data->clear();
        return $this;
    }

    /**
     * Checks whether this record is considered empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->data->isEmpty();
    }

    // <editor-fold defaultstate="collapsed" desc="[ Magic API ]">

    /**
     * Provides generic access for retrieving a record field's value
     *
     * @param string $key
     * @return mixed|null
     */
    public function __get(string $key)
    {
        return $this->getField($key);
    }

    /**
     * Provides generic access for setting a record field's value
     *
     * @param string $key
     * @param mixed|null $value
     */
    public function __set(string $key, $value)
    {
        $this->setField($key, $value);
    }

    /**
     * Provides generic access for checking a record field's existing
     *
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return $this->hasField($key);
    }

    /**
     * Provides generic access for removing a record's field
     *
     * @param string $key
     */
    public function __unset(string $key)
    {
        $this->unsetField($key);
    }

    /**
     * Provides generic access to the record's fields
     *
     * If a camel case version of a field name is used for $method name
     * with either of these prefixes
     * - get    -> getField
     * - set    -> setField
     * - unset  -> unsetField
     * - has    -> hasField
     * the corresponding field access method with the underscored
     * version of the field name is called.
     *
     * The set-wrapper will use the first and only the first argument to
     * pass to the setField() call. If the set-wrapper will be called without
     * any argument setField({field}, null) will be called.
     *
     * Examples:
     * <code>
     * $myRecord->setCaption('new Caption') <=> $myRecord->setField('caption', 'new Caption');
     * $myRecord->getCreationDate()         <=> $myRecord->getField('creation_date');
     * $myRecord->unsetCreationDate()       <=> $myRecord->unsetField('creation_date');
     * $myRecord->hasCreationDate()         <=> $myRecord->hasField('creation_date');
     * </code>
     *
     * Triggers errors (E_USER_ERROR) if unsupported method calls are processed.
     *
     * Examples leading to errors:
     * <code>
     * $myRecord->foo(); // assuming no public method foo() is provided
     * $myRecord->get_something(); // assuming no public method get_something() is provided
     * </code>
     *
     * @param string $method The name of the method invoked
     * @param array|null $args A list of arguments passed to the method invoked
     * @return mixed The return value of the method mapped
     */
    public function __call(string $method, array $args = null)
    {
        $matches = preg_match('/^(get|set|has|unset)([A-Z][a-zA-Z0-9]+)$/', $method, $match);
        if (!$matches) {
            // trigger fatal error: unsupported method call
            // mimic standard php error message
            // Fatal error: Call to undefined method {class}::{method}() in {file} on line {line}
            $backTrace = debug_backtrace();
            $errorMsg = 'Call to undefined method ' . __CLASS__ . '::' . $method . '() '
                . 'in ' . $backTrace[0]['file'] . ' on line ' . $backTrace[0]['line'];
            trigger_error($errorMsg, E_USER_ERROR);
        }

        $field = StringUtils::underscore($match[2]);
        switch ($match[1]) {
            case 'get':
                return $this->getField($field);
            case 'set':
                $value = $args[0] ?? null;
                return $this->setField($field, $value);
            case 'has':
                return $this->hasField($field);
            case 'unset':
                return $this->unsetField($field);
            default:
                return null;
        }
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
        return $this->data->getIterator();
    }

    // </editor-fold>
}