<?php

namespace Devhelp\Procedure\Model;


class Step
{
    private $identifier;
    private $arguments;

    public function __construct($identifier, array $arguments = array())
    {
        $this->identifier = $identifier;
        $this->arguments = $arguments;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
