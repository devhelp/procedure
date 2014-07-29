<?php

namespace spec\Devhelp\Procedure\Interpreter;

use Devhelp\Procedure\Model\Step;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallableInterpreterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Devhelp\Procedure\Interpreter\CallableInterpreter');
    }

    function it_throws_exception_if_first_argument_is_not_callable(Step $step)
    {
        $step->getIdentifier()->willReturn('step');

        $arguments = array(1, 2, 3);

        $this->shouldThrow('Devhelp\Procedure\Exception\StepInterpretationException')
             ->duringInterpret($step, $arguments);
    }

    function it_treats_first_argument_as_callable_and_calls_it_using_rest_as_its_arguments(Step $step)
    {
        $corePhpFunction = array('strlen', '0123456789');
        $anonymousFunction = array(function($a, $b) { return $a + $b;}, 1, 2);
        $classMethod = array(array(new \DateTime('2000-01-01', new \DateTimeZone('Europe/Warsaw')), 'getTimestamp'));

        $this->interpret($step, $corePhpFunction)->shouldReturn(10);
        $this->interpret($step, $anonymousFunction)->shouldReturn(3);
        $this->interpret($step, $classMethod)->shouldReturn(946681200);
    }
}
