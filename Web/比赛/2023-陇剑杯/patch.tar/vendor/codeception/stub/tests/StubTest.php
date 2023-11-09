<?php

declare(strict_types=1);

require_once __DIR__ .'/ResetMocks.php';

use Codeception\Stub;
use Codeception\Stub\StubMarshaler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class StubTest extends TestCase
{
    use ResetMocks;
    protected DummyClass $dummy;

    public function setUp(): void
    {
        require_once $file = __DIR__. '/_data/DummyOverloadableClass.php';
        require_once $file = __DIR__. '/_data/DummyClass.php';
        $this->dummy = new DummyClass(true);
    }

    public function testMakeEmpty()
    {
        $dummy = Stub::makeEmpty('DummyClass');
        $this->assertInstanceOf('DummyClass', $dummy);
        $this->assertTrue(method_exists($dummy, 'helloWorld'));
        $this->assertNull($dummy->helloWorld());
    }

    public function testMakeEmptyMethodReplaced()
    {
        $dummy = Stub::makeEmpty('DummyClass', ['helloWorld' => fn(): string => 'good bye world']);
        $this->assertMethodReplaced($dummy);
    }

    public function testMakeEmptyMethodSimplyReplaced()
    {
        $dummy = Stub::makeEmpty('DummyClass', ['helloWorld' => 'good bye world']);
        $this->assertMethodReplaced($dummy);
    }

    public function testMakeEmptyExcept()
    {
        $dummy = Stub::makeEmptyExcept('DummyClass', 'helloWorld');
        $this->assertEquals($this->dummy->helloWorld(), $dummy->helloWorld());
        $this->assertNull($dummy->goodByeWorld());
    }

    public function testMakeEmptyExceptPropertyReplaced()
    {
        $dummy = Stub::makeEmptyExcept('DummyClass', 'getCheckMe', ['checkMe' => 'checked!']);
        $this->assertEquals('checked!', $dummy->getCheckMe());
    }

    public function testMakeEmptyExceptMagicalPropertyReplaced()
    {
        $dummy = Stub::makeEmptyExcept('DummyClass', 'getCheckMeToo', ['checkMeToo' => 'checked!']);
        $this->assertEquals('checked!', $dummy->getCheckMeToo());
    }

    public function testFactory()
    {
        $dummies = Stub::factory('DummyClass', 2);
        $this->assertCount(2, $dummies);
        $this->assertInstanceOf('DummyClass', $dummies[0]);
    }

    public function testMake()
    {
        $dummy = Stub::make('DummyClass', ['goodByeWorld' => fn(): string => 'hello world']);
        $this->assertEquals($this->dummy->helloWorld(), $dummy->helloWorld());
        $this->assertEquals("hello world", $dummy->goodByeWorld());
    }

    public function testMakeMethodReplaced()
    {
        $dummy = Stub::make('DummyClass', ['helloWorld' => fn(): string => 'good bye world']);
        $this->assertMethodReplaced($dummy);
    }

    public function testMakeWithMagicalPropertiesReplaced()
    {
        $dummy = Stub::make('DummyClass', ['checkMeToo' => 'checked!']);
        $this->assertEquals('checked!', $dummy->checkMeToo);
    }

    public function testMakeMethodSimplyReplaced()
    {
        $dummy = Stub::make('DummyClass', ['helloWorld' => 'good bye world']);
        $this->assertMethodReplaced($dummy);
    }

    public function testCopy()
    {
        $dummy = Stub::copy($this->dummy, ['checkMe' => 'checked!']);
        $this->assertEquals('checked!', $dummy->getCheckMe());
        $dummy = Stub::copy($this->dummy, ['checkMeToo' => 'checked!']);
        $this->assertEquals('checked!', $dummy->getCheckMeToo());
    }

    public function testConstruct()
    {
        $dummy = Stub::construct('DummyClass', ['checkMe' => 'checked!']);
        $this->assertEquals('constructed: checked!', $dummy->getCheckMe());

        $dummy = Stub::construct(
            'DummyClass',
            ['checkMe' => 'checked!'],
            ['targetMethod' => fn(): bool => false]
        );
        $this->assertEquals('constructed: checked!', $dummy->getCheckMe());
        $this->assertEquals(false, $dummy->targetMethod());
    }

    public function testConstructMethodReplaced()
    {
        $dummy = Stub::construct(
            'DummyClass',
            [],
            ['helloWorld' => fn(): string => 'good bye world']
        );
        $this->assertMethodReplaced($dummy);
    }

    public function testConstructMethodSimplyReplaced()
    {
        $dummy = Stub::make('DummyClass', ['helloWorld' => 'good bye world']);
        $this->assertMethodReplaced($dummy);
    }

    public function testConstructEmpty()
    {
        $dummy = Stub::constructEmpty('DummyClass', ['checkMe' => 'checked!']);
        $this->assertNull($dummy->getCheckMe());
    }

    public function testConstructEmptyExcept()
    {
        $dummy = Stub::constructEmptyExcept('DummyClass', 'getCheckMe', ['checkMe' => 'checked!']);
        $this->assertNull($dummy->targetMethod());
        $this->assertEquals('constructed: checked!', $dummy->getCheckMe());
    }

    public function testUpdate()
    {
        $dummy = Stub::construct('DummyClass');
        Stub::update($dummy, ['checkMe' => 'done']);
        $this->assertEquals('done', $dummy->getCheckMe());
        Stub::update($dummy, ['checkMeToo' => 'done']);
        $this->assertEquals('done', $dummy->getCheckMeToo());
    }

    public function testStubsFromObject()
    {
        $dummy = Stub::make(new DummyClass());
        $this->assertInstanceOf(
            MockObject::class,
            $dummy
        );
        $dummy = Stub::make(new DummyOverloadableClass());
        $this->assertObjectHasProperty('__mocked', $dummy);
        $dummy = Stub::makeEmpty(new DummyClass());
        $this->assertInstanceOf(
            MockObject::class,
            $dummy
        );
        $dummy = Stub::makeEmpty(new DummyOverloadableClass());
        $this->assertObjectHasProperty('__mocked', $dummy);
        $dummy = Stub::makeEmptyExcept(new DummyClass(), 'helloWorld');
        $this->assertInstanceOf(
            MockObject::class,
            $dummy
        );
        $dummy = Stub::makeEmptyExcept(new DummyOverloadableClass(), 'helloWorld');
        $this->assertObjectHasProperty('__mocked', $dummy);
        $dummy = Stub::construct(new DummyClass());
        $this->assertInstanceOf(
            MockObject::class,
            $dummy
        );
        $dummy = Stub::construct(new DummyOverloadableClass());
        $this->assertObjectHasProperty('__mocked', $dummy);
        $dummy = Stub::constructEmpty(new DummyClass());
        $this->assertInstanceOf(
            MockObject::class,
            $dummy
        );
        $dummy = Stub::constructEmpty(new DummyOverloadableClass());
        $this->assertObjectHasProperty('__mocked', $dummy);
        $dummy = Stub::constructEmptyExcept(new DummyClass(), 'helloWorld');
        $this->assertInstanceOf(
            MockObject::class,
            $dummy
        );
        $dummy = Stub::constructEmptyExcept(new DummyOverloadableClass(), 'helloWorld');
        $this->assertObjectHasProperty('__mocked', $dummy);
    }

    protected function assertMethodReplaced($dummy)
    {
        $this->assertTrue(method_exists($dummy, 'helloWorld'));
        $this->assertNotEquals($this->dummy->helloWorld(), $dummy->helloWorld());
        $this->assertEquals($dummy->helloWorld(), 'good bye world');
    }

    /**
     * @return array<int, array<string|StubMarshaler>>
     */
    public static function matcherAndFailMessageProvider(): array
    {
        return [
            [Stub\Expected::atLeastOnce(),
                'Expected invocation at least once but it never'
            ],
            [Stub\Expected::once(),
                'Method was expected to be called 1 times, actually called 0 times.'
            ],
            [Stub\Expected::exactly(1),
                'Method was expected to be called 1 times, actually called 0 times.'
            ],
            [Stub\Expected::exactly(3),
              'Method was expected to be called 3 times, actually called 0 times.'
            ],
        ];
    }

    /**
     * @dataProvider matcherAndFailMessageProvider
     */
    public function testExpectedMethodIsCalledFail(StubMarshaler $stubMarshaler, string $failMessage)
    {
        $mock = Stub::makeEmptyExcept('DummyClass', 'call', ['targetMethod' => $stubMarshaler], $this);
        $mock->goodByeWorld();

        try {
            $mock->__phpunit_verify();
            $this->fail('Expected exception');
        } catch (Exception $exception) {
            $this->assertTrue(strpos($failMessage, $exception->getMessage()) >= 0, 'String contains');

        }

        $this->resetMockObjects();
    }

    public function testNeverExpectedMethodIsCalledFail()
    {
        $mock = Stub::makeEmptyExcept('DummyClass', 'call', ['targetMethod' => Stub\Expected::never()], $this);
        $mock->goodByeWorld();

        try {
            $mock->call();
        } catch (Exception $e) {
            $this->assertTrue(strpos('was not expected to be called', $e->getMessage()) >= 0, 'String contains');
        }

        $this->resetMockObjects();
    }

    /**
     * @return array<int, array<int|bool|StubMarshaler|string|null>>
     */
    public static function matcherProvider(): array
    {
        return [
            [0, Stub\Expected::never()],
            [1, Stub\Expected::once()],
            [2, Stub\Expected::atLeastOnce()],
            [3, Stub\Expected::exactly(3)],
            [1, Stub\Expected::once(fn(): bool => true), true],
            [2, Stub\Expected::atLeastOnce(fn(): array => []), []],
            [1, Stub\Expected::exactly(1, fn() => null), null],
            [1, Stub\Expected::exactly(1, fn(): string => 'hello world!'), 'hello world!'],
            [1, Stub\Expected::exactly(1, 'hello world!'), 'hello world!'],
        ];
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithMake(int $count, StubMarshaler $matcher, $expected = false)
    {
        $dummy = Stub::make('DummyClass', ['goodByeWorld' => $matcher], $this);

        $this->repeatCall($count, [$dummy, 'goodByeWorld'], $expected);
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithMakeEmpty(int $count, StubMarshaler $matcher)
    {
        $dummy = Stub::makeEmpty('DummyClass', ['goodByeWorld' => $matcher], $this);

        $this->repeatCall($count, [$dummy, 'goodByeWorld']);
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithMakeEmptyExcept(int $count, StubMarshaler $matcher)
    {
        $dummy = Stub::makeEmptyExcept('DummyClass', 'getCheckMe', ['goodByeWorld' => $matcher], $this);

        $this->repeatCall($count, [$dummy, 'goodByeWorld']);
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithConstruct(int $count, StubMarshaler $matcher)
    {
        $dummy = Stub::construct('DummyClass', [], ['goodByeWorld' => $matcher], $this);

        $this->repeatCall($count, [$dummy, 'goodByeWorld']);
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithConstructEmpty(int $count, StubMarshaler $matcher)
    {
        $dummy = Stub::constructEmpty('DummyClass', [], ['goodByeWorld' => $matcher], $this);

        $this->repeatCall($count, [$dummy, 'goodByeWorld']);
    }

    /**
     * @dataProvider matcherProvider
     */
    public function testMethodMatcherWithConstructEmptyExcept(int $count, StubMarshaler $matcher)
    {
        $dummy = Stub::constructEmptyExcept(
            'DummyClass',
            'getCheckMe',
            [],
            ['goodByeWorld' => $matcher],
            $this
        );

        $this->repeatCall($count, [$dummy, 'goodByeWorld']);
    }

    private function repeatCall($count, $callable, $expected = false)
    {
        for ($i = 0; $i < $count; ++$i) {
            $actual = call_user_func($callable);
            if ($expected) {
                $this->assertEquals($expected, $actual);
            }
        }
    }

    public function testConsecutive()
    {
        $dummy = Stub::make('DummyClass', ['helloWorld' => Stub::consecutive('david', 'emma', 'sam', 'amy')]);

        $this->assertEquals('david', $dummy->helloWorld());
        $this->assertEquals('emma', $dummy->helloWorld());
        $this->assertEquals('sam', $dummy->helloWorld());
        $this->assertEquals('amy', $dummy->helloWorld());

        // Expected null value when no more values
        $this->assertNull($dummy->helloWorld());
    }

    public function testStubPrivateProperties()
    {
        $tester = Stub::construct(
            'MyClassWithPrivateProperties',
            ['name' => 'gamma'],
            [
                 'randomName' => 'chicken',
                 't' => 'ticky2',
                 'getRandomName' => fn(): string => "randomstuff"
            ]
        );
        $this->assertEquals('gamma', $tester->getName());
        $this->assertEquals('randomstuff', $tester->getRandomName());
        $this->assertEquals('ticky2', $tester->getT());
    }

    public function testStubMakeEmptyInterface()
    {
        $stub = Stub::makeEmpty(Countable::class, ['count' => 5]);
        $this->assertEquals(5, $stub->count());
    }

    private function assertObjectHasProperty(string $propertyName, object $object): void
    {
        $hasProperty = (new ReflectionObject($object))->hasProperty($propertyName);
        $this->assertTrue($hasProperty, sprintf("Object has no attribute %s", $propertyName));
    }
}

class MyClassWithPrivateProperties
{

    private string $name       = '';

    private string $randomName = 'gaia';

    private string $t          = 'ticky';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRandomName(): string
    {
        return $this->randomName;
    }

    public function getT(): string
    {
        return $this->t;
    }
}
