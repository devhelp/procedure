<?php

namespace Devhelp\Procedure\Exception;


class MissingReferenceException extends \Exception
{
    public function __construct($reference)
    {
        parent::__construct("'$reference' reference does not exists");
    }
}
