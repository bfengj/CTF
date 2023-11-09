<?php

declare(strict_types=1);

class DummyOverloadableClass
{
    /**
     * @var int|string
     */
    protected $checkMe = 1;

    protected array $properties = ['checkMeToo' => 1];

    function __construct($checkMe = 1)
    {
        $this->checkMe = 'constructed: '.$checkMe;
    }

    /** @return string */
    public function helloWorld()
    {
        return 'hello';
    }

    /** @return string */
    public function goodByeWorld()
    {
        return 'good bye';
    }

    /** @return string */
    protected function notYourBusinessWorld()
    {
        return 'goAway';
    }

    /** @return string */
    public function getCheckMe()
    {
        return $this->checkMe;
    }

    public function getCheckMeToo()
    {
        return $this->checkMeToo;
    }

    /** @return bool */
    public function call()
    {
        $this->targetMethod();
        return true;
    }

    /** @return bool */
    public function targetMethod()
    {
        return true;
    }

    /**
     * @throws Exception
     */
    public function exceptionalMethod()
    {
        throw new Exception('Catch it!');
    }

    public function __get($name)
    {
        //seeing as we're not implementing __set here, add check for __mocked
        $return = null;
        if ($name === '__mocked') {
            $return = property_exists($this, '__mocked') && $this->__mocked !== null ? $this->__mocked : null;
        } elseif ($this->__isset($name)) {
            $return = $this->properties[$name];
        }

        return $return;
    }

    public function __isset($name)
    {
        return $this->isMagical($name) && isset($this->properties[$name]);
    }

    /** @return bool */
    private function isMagical($name)
    {
        $reflectionClass = new ReflectionClass($this);
        return !$reflectionClass->hasProperty($name);
    }
}
