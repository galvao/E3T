<?php

declare(strict_types = 1);

namespace E3T;

use \Error;
use \Exception;
use \TypeError;

class Session
{
    private ?string $id = null;
    private static ?self $instance = null;

    protected ?string $user = null;
    protected string $qualifier = '$';
    protected string $mode = '';

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            session_start([
                'cache_expire' => 0,
                'cache_limiter' => 'nocache',
                'name' => 'c3Session',
                'use_strict_mode' => 1,
                'cookie_secure' => true,
                'save_path' => dirname(realpath(__DIR__)) . '/data/session',
            ]);

            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->id = session_id();
        $this->user = getenv('USER');
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setData(string $key, mixed $value): void
    {
        if (!in_array($key, array_keys(get_object_vars($this)) or
            in_array($key, ['instance', 'modifier', 'user']))) {
            $this->cleanup();
            throw new Exception('Invalid data key.');
        } else if (isset($_SESSION[$key])) {
            $this->cleanup();
            throw new Exception('Cannot modify previously set session data.');
        }

        $_SESSION[$key] = $value;
    }

    public function getData(string $key): mixed
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else if (in_array($key, array_keys(get_object_vars($this)))) {
            return $this->$key;
        }

        $this->cleanup();
        throw new Exception('Invalid data key: ' . $key);
        return null;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    public function setMode(string $mode)
    {
        $this->mode = $mode;
    }

    public function cleanup(bool $regenerate = true): void
    {
        $_SESSION = [];

        if ($regenerate) {
            session_regenerate_id(true);
            $this->id = session_id();
        }
    }

    public function __destruct()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_register_shutdown();
            session_destroy();
            session_write_close();
        }
    }
}
