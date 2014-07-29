<?php

namespace Devhelp\Procedure\Interpreter;


use Devhelp\Procedure\Exception\StepInterpretationException;
use Devhelp\Procedure\Model\Step;

interface InterpreterInterface
{
    /**
     * @param Step $step
     * @param array $arguments
     * @throws StepInterpretationException
     * @return mixed
     */
    public function interpret(Step $step, array $arguments);
}
