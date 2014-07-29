<?php

namespace Devhelp\Procedure;


use Devhelp\Procedure\Interpreter\InterpreterInterface;
use Devhelp\Procedure\Model\Procedure;
use Devhelp\Procedure\Model\Step;

class ProcedureRunner
{
    /**
     * @var InterpreterInterface
     */
    private $interpreter;

    /**
     * @var ArgumentResolver
     */
    private $argumentResolver;

    public function setInterpreter(InterpreterInterface $interpreter)
    {
        $this->interpreter = $interpreter;
        return $this;
    }

    public function setArgumentResolver(ArgumentResolver $argumentResolver)
    {
        $this->argumentResolver = $argumentResolver;
        return $this;
    }

    /**
     * interprets all steps in the procedure returning result of interpretation of the last step
     *
     * @param Procedure $procedure
     * @return mixed
     */
    public function follow(Procedure $procedure)
    {
        $lastResult = null;

        foreach ($procedure->getSteps() as $step) {

            $resolvedArguments = $this->resolveArguments($step);

            $lastResult = $this->interpret($step, $resolvedArguments);

            $this->addToArguments($step, $lastResult);
        }

        return $lastResult;
    }

    private function resolveArguments(Step $step)
    {
        return $this->argumentResolver->resolveAll($step->getArguments());
    }

    private function interpret(Step $step, array $resolvedArguments)
    {
        return $this->interpreter->interpret($step, $resolvedArguments);
    }

    private function addToArguments(Step $step, $stepResult)
    {
        $this->argumentResolver->add($step->getIdentifier(), $stepResult);
    }
}
