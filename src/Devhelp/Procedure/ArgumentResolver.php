<?php

namespace Devhelp\Procedure;


use Devhelp\Procedure\Exception\MissingReferenceException;
use Devhelp\Procedure\Model\Reference;

class ArgumentResolver
{
    protected $argumentsPool = array();

    /**
     * returns true value of the argument. If argument is an array then it searches through
     * all the values if they need to be resolved
     *
     * @param mixed $argument
     * @return mixed
     */
    public function resolve($argument)
    {
        if ($argument instanceof Reference) {
            $value = $this->resolveReference($argument->getValue());
        } elseif (is_array($argument)) {
            $value = $this->resolveArray($argument);
        } else {
            $value = $argument;
        }

        return $value;
    }

    /**
     * resolves array of arguments
     *
     * @see resolve
     * @param array $arguments
     * @return array
     */
    public function resolveAll(array $arguments)
    {
        $resolved = array();

        foreach ($arguments as $argument) {
            $resolved[] = $this->resolve($argument);
        }

        return $resolved;
    }

    /**
     * adds real value of reference to arguments pool
     *
     * @param $reference
     * @param $value
     */
    public function add($reference, $value)
    {
        $this->argumentsPool[$reference] = $value;
    }

    private function resolveReference($reference)
    {
        $this->checkReferenceExists($reference);

        return $this->get($reference);
    }

    private function resolveArray(array $arguments)
    {
        $resolved = array();

        foreach ($arguments as $key => $argument) {
            $resolved[$key] = $this->resolve($argument);
        }

        return $resolved;
    }

    private function get($reference)
    {
        return $this->argumentsPool[$reference];
    }

    private function checkReferenceExists($reference)
    {
        if (!isset($this->argumentsPool[$reference])) {
            throw new MissingReferenceException($reference);
        }
    }
}
