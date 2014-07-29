<?php

namespace Devhelp\Procedure\Model;


class Procedure
{
    private $steps;

    public function __construct(array $steps = array())
    {
        $this->setSteps($steps);
    }

    public function setSteps(array $steps)
    {
        foreach ($steps as $step) {
            $this->addStep($step);
        }
    }

    public function addStep(Step $step)
    {
        $this->steps[$step->getIdentifier()] = $step;
    }

    public function getSteps()
    {
        return $this->steps;
    }
}
