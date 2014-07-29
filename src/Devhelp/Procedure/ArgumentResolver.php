<?php

namespace Devhelp\Procedure;


use Devhelp\Procedure\Exception\MissingReferenceException;
use Devhelp\Procedure\Model\Reference;

class ArgumentResolver
{
    protected $argumentsPool = array();

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

    public function resolveAll(array $arguments)
    {
        $resolved = array();

        foreach ($arguments as $argument) {
            $resolved[] = $this->resolve($argument);
        }

        return $resolved;
    }

    public function add($reference, $argument)
    {
        return $this->argumentsPool[$reference] = $argument;
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
