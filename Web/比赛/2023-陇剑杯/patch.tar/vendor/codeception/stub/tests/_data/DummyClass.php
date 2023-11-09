<?php

declare(strict_types=1);

class DummyClass
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

    public function __set($name, $value) 
    {
        if ($this->isMagical($name)) {
            $this->properties[$name] = $value;
        }
    }

    public function __get($name) 
    {
        if ($this->__isset($name)) {
            return $this->properties[$name];
        }
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
