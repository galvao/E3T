<?php

declare(strict_types = 1);

namespace E3T\Factory;

use \Error;
use \Exception;
use \TypeError;

use \E3T\Session;

class Command
{
    private ?object $instance = null;
    private array $args = [];
    private array $flags = [];
    private string $namespace = '\E3T\Command';

    public function __construct(string $input)
    {
        try {
            $this->parse($input);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function parse(string $input): void
    {
        $input = trim($input);
        $input = preg_replace('/^:{1}\s?/', 'mode ', $input);

        $session = Session::getInstance();

        $elements = explode(' ', $input);
        $command = ucfirst(array_shift($elements));

        if ($command !== 'Mode' and $session->getMode() === 'php') {
            eval($input);
            return;
        }

        foreach ($elements as $element) {
            if ($element[0] === '-') {
                $components = preg_split('/\s?=\s?\'?"?/', $element);
                $components[0] = ltrim($components[0], '-');

                if (str_contains($element, '=')) {
                    $components[1] = rtrim($components[1], '\'"');
                    $this->flags[$components[0]] = $components[1];
                } else {
                    $this->flags[$components[0]] = true;
                }
            } else {
                $this->args[] = trim($element, '\'"');
            }
        }

        try {
            $this->instance = new ($this->namespace . '\\' . $command);
        } catch (Error $e) {
            throw new Exception('Command ' . strtolower($command) . ' not found.');
        }

        try {
            $this->isValid(strtolower($command));
        } catch (Exception $e) {
            throw $e;
        }

        $this->instance->execute($this->flags, $this->args);
    }

    public function isValid(string $cmd): bool
    {
        $argCount = count($this->args);
        $flagCount = count($this->flags);

        if ($argCount < $this->instance->reqArgCount) {
            throw new Exception(
                'Command ' . $cmd . ' requires ' . $this->instance->reqArgCount .
                ' argument(s), ' . $argCount . ' passed.'
            );
        }

        if ($flagCount < $this->instance->reqFlagCount) {
            throw new Exception(
                'Command ' . $cmd . ' requires ' . $this->instance->reqFlagCount .
                ' flag(s), ' . $flagCount . ' passed.'
            );
        }

        return true;
    }
}
