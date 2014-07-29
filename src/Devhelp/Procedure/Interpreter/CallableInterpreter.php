<?php

namespace Devhelp\Procedure\Interpreter;


use Devhelp\Procedure\Model\Step;
use Devhelp\Procedure\Exception\StepInterpretationException;

class CallableInterpreter implements InterpreterInterface
{
    /**
     * {@inheritdoc}
     */
    public function interpret(Step $step, array $arguments)
    {
        $callable = array_shift($arguments);

        if (!is_callable($callable)) {
            throw new StepInterpretationException('Step of id ['.$step->getIdentifier().'] does not contain callable');
        }

        return call_user_func_array($callable, $arguments);
    }
}
