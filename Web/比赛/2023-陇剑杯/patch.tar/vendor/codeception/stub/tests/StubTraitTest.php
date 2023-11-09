<?php

declare(strict_types=1);

use Codeception\Stub\Expected;
use Codeception\Test\Feature\Stub;
use PHPUnit\Framework\TestCase;

require_once __DIR__ .'/ResetMocks.php';

final class StubTraitTest extends TestCase
{
    use ResetMocks;
    use Stub;

    protected DummyClass $dummy;

    public function setUp(): void
    {
        require_once $file = __DIR__. '/_data/DummyOverloadableClass.php';
        require_once $file = __DIR__. '/_data/DummyClass.php';
        $this->dummy = new DummyClass(true);
    }

    public function testMakeStubs()
    {
        $this->dummy = $this->make('DummyClass', ['helloWorld' => 'bye']);
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertEquals('good bye', $this->dummy->goodByeWorld());

        $this->dummy = $this->makeEmpty('DummyClass', ['helloWorld' => 'bye']);
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertNull($this->dummy->goodByeWorld());

        $this->dummy = $this->makeEmptyExcept('DummyClass', 'goodByeWorld', ['helloWorld' => 'bye']);
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertEquals('good bye', $this->dummy->goodByeWorld());
        $this->assertNull($this->dummy->exceptionalMethod());
    }

    public function testConstructStubs()
    {
        $this->dummy = $this->construct('DummyClass', ['!'], ['helloWorld' => 'bye']);
        $this->assertEquals('constructed: !', $this->dummy->getCheckMe());
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertEquals('good bye', $this->dummy->goodByeWorld());

        $this->dummy = $this->constructEmpty('DummyClass', ['!'], ['helloWorld' => 'bye']);
        $this->assertNull($this->dummy->getCheckMe());
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertNull($this->dummy->goodByeWorld());

        $this->dummy = $this->constructEmptyExcept('DummyClass', 'getCheckMe', ['!'], ['helloWorld' => 'bye']);
        $this->assertEquals('constructed: !', $this->dummy->getCheckMe());
        $this->assertEquals('bye', $this->dummy->helloWorld());
        $this->assertNull($this->dummy->goodByeWorld());
        $this->assertNull($this->dummy->exceptionalMethod());
    }

    public function testMakeMocks()
    {
        $this->dummy = $this->make('DummyClass', [
            'helloWorld' => Expected::once()
        ]);
        $this->dummy->helloWorld();
        try {
            $this->dummy->helloWorld();
        } catch (Exception $exception) {
            $this->assertTrue(
                strpos('was not expected to be called more than once', $exception->getMessage()) >= 0,
                'String contains'
            );
            $this->resetMockObjects();
            return;
        }

        $this->fail('No exception thrown');
    }
}
