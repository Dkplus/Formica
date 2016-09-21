<?php
declare(strict_types=1);
namespace Dkplus\Formica;

use BadMethodCallException;
use InvalidArgumentException;
use ReflectionClass;

class Builder
{
    /** @var array */
    private $arguments = [];

    /** @var string|null */
    private $staticFactoryMethod;

    public function __construct(array $arguments, string $staticFactoryMethod = null, string $class = null)
    {
        $this->arguments = $arguments;
        $this->staticFactoryMethod = $staticFactoryMethod;
        $this->class = $class === null ? substr(__CLASS__, 0, -7) : $class;
    }

    protected function setArgument($name, $value)
    {
        if (! array_key_exists($name, $this->arguments)) {
            throw new InvalidArgumentException("There is no argument $name");
        }
        $this->arguments[$name] = $value;
    }

    public function __call($name, $arguments): self
    {
        if (count($arguments) !== 1) {
            throw new InvalidArgumentException('There must be exactly one argument');
        }
        if (strpos($name, 'with') === 0) {
            $property = substr($name, 4);
        }
        if (strpos($name, 'and') === 0) {
            $property = substr($name, 3);
        }
        if (isset($property)) {
            $property = strtolower(substr($property, 0, 1)) . substr($property, 1);
            $this->setArgument($property, current($arguments));
            return $this;
        }
        throw new BadMethodCallException("Method $name is not supported. Supported methods are with*() and and*()");
    }

    public function build()
    {
        if (! $this->staticFactoryMethod) {
            $reflection = new ReflectionClass($this->class);
            return $reflection->newInstanceArgs($this->arguments);
        }
        return call_user_func_array([$this->class, $this->staticFactoryMethod], $this->arguments);
    }
}
