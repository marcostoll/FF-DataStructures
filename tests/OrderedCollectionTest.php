<?php
/**
 * Definition of OrderedCollectionTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\DataStructures;

use FF\DataStructures\OrderedCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test OrderedCollectionTest
 *
 * @package FF\Tests
 */
class OrderedCollectionTest extends TestCase
{
    const SOME_ITEMS = ['1st', '2nd', '3rd', '4th'];

    /**
     * @var OrderedCollection
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new OrderedCollection();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetItems()
    {
        $this->uut->setItems(['a' => 42, 'b' => 666]);
        $this->assertEquals([0, 1], $this->uut->getKeys());
        $this->assertEquals(42, $this->uut[0]);
        $this->assertEquals(666, $this->uut->get(1));
        $this->assertFalse($this->uut->has(2));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testHasNotInt()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->has('foo');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetNotInt()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->get('foo');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetNotInt()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->set('foo', 'bar');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetToNull()
    {
        $this->uut->setItems(self::SOME_ITEMS)->set(1, null);
        $this->assertEquals('3rd', $this->uut[1]);
        $this->assertEquals(3, count($this->uut));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetPush()
    {
        $newItem = '5th';
        $this->uut->setItems(self::SOME_ITEMS)->set(42, $newItem);
        $this->assertEquals($newItem, $this->uut[4]);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetNegativeOffset()
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->uut->set(- 1, 'bar');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testUnsetNotInt()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->unset('foo');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetFirst()
    {
        $this->assertNull($this->uut->getFirst());
        $this->assertEquals(self::SOME_ITEMS[0], $this->uut->setItems(self::SOME_ITEMS)->getFirst());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetLast()
    {
        $this->assertNull($this->uut->getLast());
        $this->assertEquals(
            self::SOME_ITEMS[count(self::SOME_ITEMS) - 1],
            $this->uut->setItems(self::SOME_ITEMS)->getLast()
        );
    }

    /**
     * Tests the namesake method/feature
     */
    public function testTruncate()
    {
        $same = $this->uut->setItems(self::SOME_ITEMS)->truncate(2);
        $this->assertSame($this->uut, $same);
        $this->assertEquals(2, count($this->uut));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testTruncateNegativeLength()
    {
        $this->expectException(\OutOfBoundsException::class);

        $this->uut->truncate(- 1);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testPush()
    {
        $same = $this->uut->setItems(self::SOME_ITEMS)->push('foo');
        $this->assertSame($this->uut, $same);
        $this->assertEquals('foo', $this->uut->getLast());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testPushMultiple()
    {
        $this->uut->push(...self::SOME_ITEMS);
        $this->assertEquals(self::SOME_ITEMS, $this->uut->getItems());

    }

    /**
     * Tests the namesake method/feature
     */
    public function testUnshift()
    {
        $same = $this->uut->setItems(self::SOME_ITEMS)->unshift('foo');
        $this->assertSame($this->uut, $same);
        $this->assertEquals('foo', $this->uut->getFirst());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testUnshiftMultiple()
    {
        $this->uut->unshift(...self::SOME_ITEMS);
        $this->assertEquals(self::SOME_ITEMS, $this->uut->getItems());

    }

    /**
     * Tests the namesake method/feature
     */
    public function testShift()
    {
        $first = $this->uut->setItems(self::SOME_ITEMS)->getFirst();
        $length = count($this->uut);
        $value = $this->uut->shift();
        $this->assertSame($first, $value);
        $this->assertEquals($length - 1, count($this->uut));

        for ($i = 0; $i < count(self::SOME_ITEMS) - 1; $i++) {
            $this->uut->shift();
        }
        $this->assertTrue($this->uut->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAppendArray()
    {
        $newValues = ['foo' => 'bar', 'fii' => 'baz'];
        $same = $this->uut->setItems(self::SOME_ITEMS)->append($newValues);
        $this->assertSame($this->uut, $same);
        $this->assertSame(count(self::SOME_ITEMS) + count($newValues), count($this->uut));
        $this->assertSame('bar', $this->uut[count(self::SOME_ITEMS)]);
        $this->assertSame('baz', $this->uut->getLast());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAppendCollection()
    {
        $collection = new \FF\DataStructures\Collection(['foo' => 'bar', 'fii' => 'baz']);
        $same = $this->uut->setItems(self::SOME_ITEMS)->append($collection);
        $this->assertSame($this->uut, $same);
        $this->assertSame(count(self::SOME_ITEMS) + count($collection), count($this->uut));
        $this->assertSame('bar', $this->uut[count(self::SOME_ITEMS)]);
        $this->assertSame('baz', $this->uut->getLast());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testAppendInvalidArg()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->uut->append('foo');
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSort()
    {
        $naturalSort = function($itemA, $itemB) {
            return $itemA <=> $itemB;
        };

        $shuffledItems = self::SOME_ITEMS;
        shuffle($shuffledItems);

        $this->uut->setItems($shuffledItems);
        $same = $this->uut->sort($naturalSort);
        $this->assertSame($this->uut, $same);

        usort($shuffledItems, $naturalSort);
        $this->assertSame($shuffledItems, $this->uut->getItems());
    }
}
