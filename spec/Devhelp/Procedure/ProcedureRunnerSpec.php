<?php

namespace spec\Devhelp\Procedure;

use Devhelp\Procedure\ArgumentResolver;
use Devhelp\Procedure\Interpreter\InterpreterInterface;
use Devhelp\Procedure\Model\Procedure;
use Devhelp\Procedure\Model\Step;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProcedureRunnerSpec extends ObjectBehavior
{
    const STEP_NAME_A = 'step_a';
    const STEP_NAME_B = 'step_b';
    const STEP_NAME_C = 'step_c';

    const STEP_RESULT_A = 'result_a';
    const STEP_RESULT_B = 'result_b';
    const STEP_RESULT_C = 'result_c';

    protected $argumentResolver;
    protected $interpreter;
    protected $procedure;
    protected $stepA;
    protected $stepB;
    protected $stepC;
    protected $lastResult;

    function let(
        ArgumentResolver $argumentResolver,
        InterpreterInterface $interpreter,
        Procedure $procedure,
        Step $stepA,
        Step $stepB,
        Step $stepC
    ) {
        $this->argumentResolver = $argumentResolver;
        $this->interpreter = $interpreter;

        $this->procedure = $procedure;
        $this->stepA = $stepA;
        $this->stepB = $stepB;
        $this->stepC = $stepC;

        $stepA->getIdentifier()->willReturn(self::STEP_NAME_A);
        $stepB->getIdentifier()->willReturn(self::STEP_NAME_B);
        $stepC->getIdentifier()->willReturn(self::STEP_NAME_C);


        $stepA->getArguments()->willReturn(array());
        $stepB->getArguments()->willReturn(array());
        $stepC->getArguments()->willReturn(array());

        $this->setArgumentResolver($argumentResolver);
        $this->setInterpreter($interpreter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Devhelp\Procedure\ProcedureRunner');
    }

    function it_follows_procedure_step_by_step_returning_last_result()
    {
        $this->givenStepOrderInProcedureIs(array($this->stepA, $this->stepB, $this->stepC));
        $this->thenArgumentShouldResolveStepArguments();
        $this->thenInterpreterShouldReturnStepResults();
        $this->thenStepResultsShouldBeAddedToArgumentsPool();
        $this->andResultShouldBe(self::STEP_RESULT_C);
        $this->whenFollowIsCalled();
    }

    private function givenStepOrderInProcedureIs($steps)
    {
        $this->procedure->getSteps()->willReturn($steps);
    }

    private function andResultShouldBe($lastResult)
    {
        $this->lastResult = $lastResult;
    }

    private function whenFollowIsCalled()
    {
        $this->follow($this->procedure)->shouldReturn($this->lastResult);
    }

    private function thenArgumentShouldResolveStepArguments()
    {
        $this->argumentResolver->resolveAll(Argument::any())->shouldBeCalled()->willReturn(array());
    }

    private function thenInterpreterShouldReturnStepResults()
    {
        $this->interpreter->interpret($this->stepA, Argument::type('array'))->willReturn(self::STEP_RESULT_A);
        $this->interpreter->interpret($this->stepB, Argument::type('array'))->willReturn(self::STEP_RESULT_B);
        $this->interpreter->interpret($this->stepC, Argument::type('array'))->willReturn(self::STEP_RESULT_C);
    }

    private function thenStepResultsShouldBeAddedToArgumentsPool()
    {
        $this->argumentResolver->add(self::STEP_NAME_A, self::STEP_RESULT_A)->shouldBeCalled();
        $this->argumentResolver->add(self::STEP_NAME_B, self::STEP_RESULT_B)->shouldBeCalled();
        $this->argumentResolver->add(self::STEP_NAME_C, self::STEP_RESULT_C)->shouldBeCalled();
    }

}
