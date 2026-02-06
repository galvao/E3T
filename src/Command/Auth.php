<?php

declare(strict_types = 1);

namespace E3T\Command;

class Auth
{
    public readonly int $reqFlagCount;
    public readonly int $reqArgCount;

    public function __construct()
    {
        $this->reqFlagCount = 0;
        $this->reqArgCount = 1;
    }

    public function execute()
    {
        echo 'Password: ';
        $pass = readline();

        if (password_verify($pass, '$argon2id$v=19$m=65536,t=4,p=1$c2NaT0Z5UHlCSENFS0RRSg$lbgg1Z/SpntOFi+8HEgwPcC14DnVQtM5rR1Ul+chyIA')) {
            echo 'OK' . PHP_EOL;
        } else {
            echo 'ERR' . PHP_EOL;
        }
    }
}
