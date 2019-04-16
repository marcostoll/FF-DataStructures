<?php
/**
 * Definition of IndexedCollectionTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\DataStructures;

use FF\DataStructures\IndexedCollection;
use PHPUnit\Framework\TestCase;

/**
 * Test IndexedCollectionTest
 *
 * @package FF\Tests
 */
class IndexedCollectionTest extends TestCase
{
    const SOME_ITEMS = ['a' => 'foo', 'b' => 'bar', 'c' => 'baz'];

    /**
     * @var IndexedCollection
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new IndexedCollection();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testHas()
    {
        $this->assertFalse($this->uut->has('void'));

        $this->uut->setItems(self::SOME_ITEMS);
        $this->assertTrue($this->uut->has('c'));
        $this->assertFalse($this->uut->has('foo'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGet()
    {
        $this->uut->setItems(self::SOME_ITEMS);
        $this->assertEquals('bar', $this->uut->get('b'));
        $this->assertNull($this->uut->get('void'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSet()
    {
        $same = $this->uut->set(0, 'foo');
        $this->assertSame($this->uut, $same);

        $this->uut->setItems(self::SOME_ITEMS);
        $newValue = 'new';
        $this->assertEquals($newValue, $this->uut->set('c', $newValue)->get('c'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testUnset()
    {
        $same = $this->uut->setItems(self::SOME_ITEMS)->unset('a');
        $this->assertSame($this->uut, $same);
        $this->assertNull($this->uut->get('a'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetKeys()
    {
        $keys = $this->uut->setItems(self::SOME_ITEMS)->getKeys();
        $this->assertSame(count($this->uut), count($keys));
        foreach ($keys as $key) {
            $this->assertTrue($this->uut->has($key));
        }
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSearch()
    {
        $this->uut->setItems(self::SOME_ITEMS);
        $this->assertEquals('b', $this->uut->search('bar'));
        $this->assertNull($this->uut->search('void'));

        $this->uut->set('d', 'bar');
        $this->assertEquals('b', $this->uut->search('bar'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testArrayAccess()
    {
        $this->uut->setItems(self::SOME_ITEMS);

        $this->assertTrue(isset($this->uut['b']));
        $this->assertFalse(isset($this->uut['void']));

        $this->assertEquals('foo', $this->uut['a']);
        $this->assertNull($this->uut['void']);

        $newValue = 'new';
        $this->uut['d'] = $newValue;
        $this->assertEquals($newValue, $this->uut->get('d'));

        unset($this->uut['d']);
        $this->assertFalse($this->uut->has('d'));
    }
}
