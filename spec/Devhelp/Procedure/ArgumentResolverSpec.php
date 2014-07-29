<?php

namespace spec\Devhelp\Procedure;

use Devhelp\Procedure\Model\Reference;
use PhpSpec\ObjectBehavior;

class ArgumentResolverSpec extends ObjectBehavior
{
    const TEST_REFERENCE_A = 'test_reference_a';
    const TEST_REFERENCE_B = 'test_reference_b';
    const TEST_REFERENCE_C = 'test_reference_c';
    const NON_EXISTING_REFERENCE = 'test_reference';

    const TEST_VALUE_A = 'test_value_a';
    const TEST_VALUE_B = 'test_value_b';
    const TEST_VALUE_C = 'test_value_c';
    const TEST_VALUE_D = 'test_value_d';

    function let()
    {
        $this->add(self::TEST_REFERENCE_A, self::TEST_VALUE_A);
        $this->add(self::TEST_REFERENCE_B, self::TEST_VALUE_B);
        $this->add(self::TEST_REFERENCE_C, self::TEST_VALUE_C);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Devhelp\Procedure\ArgumentResolver');
    }

    function it_throws_exception_if_reference_does_not_exist(Reference $argument)
    {
        $argument->getValue()->willReturn(self::NON_EXISTING_REFERENCE);

        $this->shouldThrow('Devhelp\Procedure\Exception\MissingReferenceException')->duringResolve($argument);
    }

    function it_resolves_real_value_of_non_reference_argument_to_the_same_argument()
    {
        $argument = self::TEST_VALUE_D;

        $resolvedArgument = $argument;

        $this->resolve($argument)->shouldReturn($resolvedArgument);
    }

    function it_resolves_real_value_of_reference(Reference $reference)
    {
        $reference->getValue()->willReturn(self::TEST_REFERENCE_A);

        $resolvedArgument = self::TEST_VALUE_A;

        $this->resolve($reference)->shouldReturn($resolvedArgument);
    }

    function it_resolves_real_values_if_references_are_part_of_the_array(
        Reference $referenceA,
        Reference $referenceB,
        Reference $referenceC
    ) {
        $referenceA->getValue()->willReturn(self::TEST_REFERENCE_A);
        $referenceB->getValue()->willReturn(self::TEST_REFERENCE_B);
        $referenceC->getValue()->willReturn(self::TEST_REFERENCE_C);

        $argument = array(
            'key_1' => array(
                'key_11' => array(
                    'key_111' => $referenceA,
                    'key_112' => self::TEST_VALUE_D,
                ),
                'key_12' => $referenceB
            ),
            'key_2' => $referenceC,
        );

        $resolvedArgument = array(
            'key_1' => array(
                'key_11' => array(
                    'key_111' => self::TEST_VALUE_A,
                    'key_112' => self::TEST_VALUE_D,
                ),
                'key_12' => self::TEST_VALUE_B,
            ),
            'key_2' => self::TEST_VALUE_C,
        );

        $this->resolve($argument)->shouldReturn($resolvedArgument);
    }

    function it_resolves_collection_of_arguments(Reference $referenceA, Reference $referenceB,Reference $referenceC)
    {
        $referenceA->getValue()->willReturn(self::TEST_REFERENCE_A);
        $referenceB->getValue()->willReturn(self::TEST_REFERENCE_B);
        $referenceC->getValue()->willReturn(self::TEST_REFERENCE_C);

        $arguments = array(
            array(
                'key_1' => array(
                    'key_11' => $referenceA,
                    'key_12' => self::TEST_VALUE_D,
                ),
                'key_2' => $referenceB
            ),
            $referenceC,
        );

        $resolvedArguments = array(
            array(
                'key_1' => array(
                    'key_11' => self::TEST_VALUE_A,
                    'key_12' => self::TEST_VALUE_D,
                ),
                'key_2' => self::TEST_VALUE_B,
            ),
            self::TEST_VALUE_C,
        );

        $this->resolveAll($arguments)->shouldReturn($resolvedArguments);
    }
}
