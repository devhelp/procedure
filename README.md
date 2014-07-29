[![Build Status](https://travis-ci.org/devhelp/procedure.png)](https://travis-ci.org/devhelp/procedure)  [![SensioLabsInsight](https://insight.sensiolabs.com/projects/f201687c-8951-4ea9-9fce-aed0a4d2046a/mini.png)](https://insight.sensiolabs.com/projects/f201687c-8951-4ea9-9fce-aed0a4d2046a)

## Installation

Composer is preferred to install devhelp/procedure, please check [composer website](http://getcomposer.org) for more information.

```
$ composer require 'devhelp/procedure:dev-master'
```

## Purpose

devhelp/procedure introduces mechanism that allows you to define steps with its arguments that
are going to be interpreted(*) in order they are defined in the procedure.

"interpreted" can mean, for example, "called" if such interpreter will be set to interpret the steps.

devhelp/procedure is shipped with CallableInterpreter, example usage is shown below.

## Usage

```
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Devhelp\Procedure\ProcedureRunner;
use Devhelp\Procedure\ArgumentResolver;
use Devhelp\Procedure\Interpreter\CallableInterpreter;
use Devhelp\Procedure\Model\Procedure;
use Devhelp\Procedure\Model\Step;
use Devhelp\Procedure\Model\Reference;

//example class
class Math
{
    public static function add($a, $b) {
        return $a + $b;
    }

    public static function mul($a, $b) {
        return $a * $b;
    }
}

/**
 * because we are using CallableInterpreter in the example, first argument
 * of step arguments is a callback
 */

$stepOne = new Step('add_1', array('Math::add', 1, 1));
//1 + 1 = 2 (store result as 'add_1')

$stepTwo = new Step('add_2', array('Math::add', 2, 2));
//2 + 2 = 4 (store result as 'add_2')

$stepThree = new Step('mul', array('Math::mul', new Reference('add_1'), new Reference('add_2')));
//2 * 4 = 8 (store result as 'mul')

$stepFour = new Step('dec2bin', array('decbin', new Reference('mul')));
//decbin(8) = '1000' (store result as 'dec2bin', but since this is last step it does not matter)

$procedure = new Procedure(array($stepOne, $stepTwo, $stepThree, $stepFour));


$argumentResolver = new ArgumentResolver();
$interpreter = new CallableInterpreter();

$runner = new ProcedureRunner();
$runner->setArgumentResolver($argumentResolver);
$runner->setInterpreter($interpreter);

echo $runner->follow($procedure), PHP_EOL; //returns result of last step ('1000')
```

## FAQ

### How can I change interpretation

You can write own interpreter class and set it in ProcedureRunner.
It has to implement Devhelp\Procedure\Interpreter\InterpreterInterface.

```
<?php

namespace Acme\Demo\Interpreter;


use Devhelp\Procedure\Model\Step;
use Devhelp\Procedure\Exception\StepInterpretationException;
use Devhelp\Procedure\Interpreter\InterpreterInterface;

class MyCustomInterpreter implements InterpreterInterface
{
    /**
     * {@inheritdoc}
     */
    public function interpret(Step $step, array $arguments)
    {
        /**
         * my custom logic that interprets the step
         */
    }
}
```


## Credits

Brought to you by : Devhelp.pl (http://devhelp.pl)