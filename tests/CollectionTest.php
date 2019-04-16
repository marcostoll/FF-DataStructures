<?php
/**
 * Definition of CollectionTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\DataStructures;

use FF\DataStructures\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Test CollectionTest
 *
 * @package FF\Tests
 */
class CollectionTest extends TestCase
{
    const SOME_ITEMS = [0, 1, true, false, 'a', 'b', null];

    /**
     * @var Collection
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new Collection();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGenericConstructor()
    {
        $collection = new Collection();
        $this->assertEmpty($collection->getItems());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetItems()
    {
        $same = $this->uut->setItems(self::SOME_ITEMS);
        $this->assertSame($this->uut, $same);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetItems()
    {
        $items = $this->uut->getItems();
        $this->assertIsArray($items);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetItems()
    {
        $items = $this->uut->setItems(self::SOME_ITEMS)->getItems();
        $this->assertEquals(self::SOME_ITEMS, $items);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsEmpty()
    {
        $this->assertTrue($this->uut->isEmpty());
        $this->assertFalse($this->uut->setItems(self::SOME_ITEMS)->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testClear()
    {
        $same = $this->uut->setItems(self::SOME_ITEMS)->clear();
        $this->assertSame($this->uut, $same);
        $this->assertTrue($this->uut->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetLength()
    {
        $this->assertEquals(0, $this->uut->getLength());
        $this->assertEquals(count(self::SOME_ITEMS), $this->uut->setItems(self::SOME_ITEMS)->getLength());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMap()
    {
        $callback = function($item) {
            return (string)$item . '::' . gettype($item);
        };

        $this->uut->setItems(self::SOME_ITEMS);

        $mappedItems = $this->uut->map($callback);
        $this->assertIsArray($mappedItems);
        $this->assertEquals(count($mappedItems), $this->uut->getLength());

        foreach (self::SOME_ITEMS as $item) {
            $this->assertIsInt(array_search($callback($item), $mappedItems));
        }
    }

    /**
     * Tests the namesake method/feature
     */
    public function testFilter()
    {
        $filterString = function($item) {
            return is_string($item);
        };

        $filterAll = function($item) {
            return false;
        };

        $this->uut->setItems(self::SOME_ITEMS);
        $same = $this->uut->filter($filterString);
        $this->assertSame($this->uut, $same);

        foreach ($this->uut as $item) {
            $this->assertIsString($item);
        }

        $this->assertTrue($this->uut->filter($filterAll)->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCountable()
    {
        $this->assertEquals(0, count($this->uut));
        $this->assertEquals(count(self::SOME_ITEMS), count($this->uut->setItems(self::SOME_ITEMS)));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsIterable()
    {
        $this->assertIsIterable($this->uut);
        $this->uut->setItems(self::SOME_ITEMS);

        $itemsVisited = [];
        foreach ($this->uut as $item) {
            $itemsVisited[] = $item;
        }
        $this->assertEquals(self::SOME_ITEMS, $itemsVisited);
    }
}
