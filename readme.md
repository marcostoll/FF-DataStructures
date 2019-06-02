FF\DataStructures | Fast Forward Components Collection
===============================================================================

by Marco Stoll

- <marco.stoll@rocketmail.com>
- <http://marcostoll.de>
- <https://github.com/marcostoll>
- <https://github.com/marcostoll/FF-DataStructures>
------------------------------------------------------------------------------------------------------------------------

# What is the Fast Forward Components Collection?
The Fast Forward Components Collection, in short **Fast Forward** or **FF**, is a loosely coupled collection of code 
repositories each addressing common problems while building web application. Multiple **FF** components may be used 
together if desired. And some more complex **FF** components depend on other rather basic **FF** components.

**FF** is not a framework in and of itself and therefore should not be called so. 
But you may orchestrate multiple **FF** components to build an web application skeleton that provides the most common 
tasks.

# Introduction

The **FF\DataStructures** component provides generic implementations of common data structure patterns. Instances of the
various data structure classes are designed to hold arbitrary data and provide apis to access and manipulate them.

# The Data Structures Classes

## Records

A record is by definition a construct holding a flat list of data each identified by a unique string key. The **FF**
approach regards the data elements as the record's *fields* and their identifiers a the *key*. The implementation 
provides method to add/remove/manipulate single fields by using its key or a bunch if fields. You ma `clear()` a record
or check if it `isEmpty()`.

THe `Record` implements the `IteratorAggregate` interface. So you may just iterate over its keys and fields.

    use FF\DataStructures\Record;
    
    $myRecord = new Record([
        'foo' => 'bar',
        'my_field' => 42
    ]);

    foreach ($myRecord as $key => $field) {
        var_dump($key, $field);
    } 

### Magic

If you constrict your keys to a pattern where they only consists of lowercase words with underscores (_) as separators
not containing any whitespaces you unlock the `Record` classes magic api for accessing and manipulating fields.

In this case instead of using the generic `getField()`, `setField()`, `hasField()`, and `unsetField()` methods that each
require the field's key as a parameter you may use named methods composed of the field's key instead.

An example:

    use FF\DataStructures\Record;
    
    $myRecord = new Record([
        'foo' => 'bar',
        'my_field' => 42
    ]);
    
    $foo = $myRecord->getFoo();         // equivalent to $myRecord->getField('foo');
    $myRecord->setFoo('baz');           // equivalent to $myRecord->setField('foo', 'baz');
    $check = $myRecord->hasMyField();   // equivalent to $myRecord->hasField(''my_field');
    $myRecord->unsetMyField();          // equivalent to $myRecord->unsetField(''my_field');
    
So instead of providing the field's key as (underscored, lowercased) string, you rather use the field's magic method
with method names composed of 'get', 'set', 'has', or 'unset' followed by the studly-caps (a capital letter as the first
character of each word within the underscored version)  version of the field's key.

## Collections

### Generic Collections

Collections are in their most basic form a set of unordered elements. The **FF** implementation lets you store an 
arbitrary amount of elements within a collection and provides methods to retrieve and replace its contents, count
the number of elements via `getLength()` or `count($myCollection)`, `clear()` the entire collection, check if it 
`isEmpty()`, or just iterate over its elements within a loop.

Additional you may either `filter()` its contents by using a custom filter callback or `map()` its set of elements
using a custom mapping callback.

**Beware**: A generic `Collection` instance provides no means of accessing a single of its elements because it has not
any knowledge of any identifiers or any ordering of the elements it contains.

### Indexed Collections

The elements of an `IndexedCollection` can additionally by accessed via an identifier known as the **index**. This lets
you access, manipulate or `search()` single elements within the collection by using its index. The class implements the
`ArrayAccess` interface that lets you use instances of this class like an array.

    use FF\DataStructures\IndexedCollection;
    
    $myCollection = new IndexCollection(['foo', 'bar', 'baz' => 42]);
    
    var_dump(
        $myCollection[0],
        $myCollection[1],
        $myCollection['baz]
    );
    
**Beware**: An `IndexedCollection` instance is not aware of any ordering within its set of elements.

### Ordered Collection

On top of the `IndexedCollection` their is the `OrderedCollection`. This class comes with an implementation of an 
ordered set of elements. So it's aware of its first or last element and lets you `append()` new elements to the end
of its set or `truncate()` the amount of stored elements to a specific length.
It event comes with a set of methods the use it as a stack.

    use FF\DataStructures\OrderedCollection;
        
    $myCollection = new OrderedCollection();
    
    $myCollection->push('foo')->push('bar')->unshift('baz');    // contains now 'baz', foo', 'bar'
    var_dump($myCollection->pop());                             // contains now 'baz', 'foo'
    var_dump($myCollection->shift());                           // contains now 'foo' 

# Road Map

The extend of the **DataStructures** component is mainly defined by the needs of other **FF** components. So its code
base will grow as new feature requests pop of within the **FF** components collection.

Most likely tree and queue implementations will be added to the list of provided data structures in the near future.