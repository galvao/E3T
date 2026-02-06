<?php

declare(strict_types = 1);

namespace E3T;

use \Error;
use \Exception;
use \TypeError;

use E3T\Session;

class E3T
{
    private ?string $prompt = null;
    private ?string $host = null;
    private ?Session $session = null;

    public function __construct(Session $session)
    {
        $this->host = getenv('HOST');
        $this->session = $session;

    }

    public function getPrompt(): string
    {
        return sprintf(
            '%s@%s%s%s > ',
            $this->session->getData('user'),
            $this->host,
            ($this->session->getData('mode') === '' ? '' : ':' . $this->session->getData('mode')),
            $this->session->getData('qualifier')
        );
    }

    private function parse(string $input)
    {
        $input = trim($input);

        if (preg_match('/^[^\w\s]{1}/', $input, $matches)) {
            $this->modChar = $matches[0];
            $input = substr($input, 1);
        }

        $elements = explode(' ', $input);
        $command = ucfirst(array_shift($elements));

        try {
            $this->instance = new ('\E3T\Command\\' . $command);
        } catch (Error $e) {
            throw new Exception('Command ' . $input . ' not found.' . PHP_EOL);
        }

        $this->instance->execute();
    }

    private function validate(): bool
    {
    }

}




