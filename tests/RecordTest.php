<?php
/**
 * Definition of RecordTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\DataStructures;

use FF\DataStructures\IndexedCollection;
use FF\DataStructures\Record;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;

/**
 * Test RecordTest
 *
 * @package FF\Tests
 */
class RecordTest extends TestCase
{
    const SOME_DATA = ['foo' => 'bar', 'fii' => 'baz', 'under_scored' => 'camelCase'];

    /**
     * @var Record
     */
    protected $uut;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new Record();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGenericConstructor()
    {
        $this->uut = new Record();
        $this->assertTrue($this->uut->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetDataArray()
    {
        $same = $this->uut->setData(self::SOME_DATA);
        $this->assertSame($this->uut, $same);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetDataCollection()
    {
        $same = $this->uut->setData(new IndexedCollection(self::SOME_DATA));
        $this->assertSame($this->uut, $same);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetData()
    {
        $data = $this->uut->getData();
        $this->assertInstanceOf(IndexedCollection::class, $data);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetData()
    {
        $data = $this->uut->setData(self::SOME_DATA)->getData();
        $this->assertEquals(self::SOME_DATA, $data->getItems());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetDataAsArray()
    {
        $data = $this->uut->setData(new IndexedCollection(self::SOME_DATA))->getDataAsArray();
        $this->assertEquals(self::SOME_DATA, $data);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIsEmpty()
    {
        $this->assertTrue($this->uut->isEmpty());
        $this->assertFalse($this->uut->setData(self::SOME_DATA)->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testClear()
    {
        $same = $this->uut->setData(self::SOME_DATA)->clear();
        $this->assertSame($this->uut, $same);
        $this->assertTrue($this->uut->isEmpty());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testHasField()
    {
        $this->assertFalse($this->uut->hasField('void'));

        $this->uut->setData(self::SOME_DATA);
        $this->assertTrue($this->uut->hasField('foo'));
        $this->assertFalse($this->uut->hasField('faa'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetField()
    {
        $this->uut->setData(self::SOME_DATA);
        $this->assertEquals('bar', $this->uut->getField('foo'));
        $this->assertNull($this->uut->getField('void'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testGetFieldDefault()
    {
        $this->uut->setData(self::SOME_DATA);
        $this->assertEquals('foo', $this->uut->getField('void', 'foo'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetField()
    {
        $same = $this->uut->setField('foo', 'bar');
        $this->assertSame($this->uut, $same);

        $this->uut->setData(self::SOME_DATA);
        $newValue = 'new';
        $this->assertEquals($newValue, $this->uut->setField('foo', $newValue)->getField('foo'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testUnsetField()
    {
        $same = $this->uut->setData(self::SOME_DATA)->unsetField('fii');
        $this->assertSame($this->uut, $same);
        $this->assertNull($this->uut->getField('fii'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicHas()
    {
        $this->assertFalse(isset($this->uut->void));

        $this->uut->setData(self::SOME_DATA);
        $this->assertTrue(isset($this->uut->foo));
        $this->assertFalse(isset($this->uut->faa));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicGet()
    {
        $this->uut->setData(self::SOME_DATA);
        $this->assertEquals('bar', $this->uut->foo);
        $this->assertNull($this->uut->void);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicSet()
    {
        $this->uut->setData(self::SOME_DATA);
        $newValue = 'new';
        $this->uut->foo = $newValue;
        $this->assertEquals($newValue, $this->uut->getField('foo'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicUnset()
    {
        $this->uut->setData(self::SOME_DATA);
        unset($this->uut->fii);
        $this->assertNull($this->uut->getField('fii'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCallGet()
    {
        $this->uut->setData(self::SOME_DATA);

        $this->assertEquals('foo', $this->uut->getNonExistingField('foo'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCallSet()
    {
        $this->uut->setData(self::SOME_DATA);

        $same = $this->uut->setMyField('new value');
        $this->assertSame($this->uut, $same);
        $this->assertEquals('new value', $this->uut->getField('my_field'));

        $this->uut->setFoo('value');
        $this->assertTrue($this->uut->hasField('foo'));
        $this->assertEquals('value', $this->uut->getField('foo'));

        $this->uut->setFooBar('value');
        $this->assertTrue($this->uut->hasField('foo_bar'));
        $this->assertEquals('value', $this->uut->getField('foo_bar'));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCallHas()
    {
        $this->uut->setData(self::SOME_DATA);

        $this->assertTrue($this->uut->hasUnderScored());
        $this->assertFalse($this->uut->hasNonExistingField());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCallUnset()
    {
        $this->uut->setData(self::SOME_DATA);

        $same = $this->uut->unsetFii();
        $this->assertSame($this->uut, $same);
        $this->assertFalse($this->uut->hasField('fii'));
    }

    /**
     * Tests the namesake method/feature
     *
     * ExceptionCode 256: E_USER_ERROR
     */
    public function testCallUndefinedMethod()
    {
        $this->expectException(Error::class);

        $this->uut->undefinedMethod();
    }

    /**
     * Tests the namesake method
     *
     * ExceptionCode 256: E_USER_ERROR
     */
    public function testCallUndefinedMethod2()
    {
        $this->expectException(Error::class);

        $this->uut->get_something();
    }

    /**
     * Tests the namesake method/feature
     */
    public function testCountable()
    {
        var_dump($this->uut);
        $this->assertEquals(0, count($this->uut));
        $this->assertEquals(count(self::SOME_DATA), count($this->uut->setData(self::SOME_DATA)));
    }

    /**
     * Tests the namesake method/feature
     */
    public function testIteratorAggregate()
    {
        $this->uut->setData(self::SOME_DATA);

        foreach ($this->uut as $field => $value) {
            $this->assertArrayHasKey($field, self::SOME_DATA);
            $this->assertEquals(self::SOME_DATA[$field], $value);
        }
    }
}
