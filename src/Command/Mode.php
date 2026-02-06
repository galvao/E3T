<?php

declare(strict_types = 1);

namespace E3T\Command;

use \E3T\Session;

class Mode
{
    public readonly int $reqFlagCount;
    public readonly int $reqArgCount;

    public function __construct()
    {
        $this->reqFlagCount = 0;
        $this->reqArgCount = 1;
    }

    public function execute(array $flags, array $args)
    {
        $session = Session::getInstance();
        $session->setMode($args[0]);
    }
}

