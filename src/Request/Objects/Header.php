<?php

namespace CaioMarcatti12\Data\Request\Objects;

use CaioMarcatti12\Core\Validation\Assert;

class Header
{
    private static array $payload = [];

    public static function add($key, $value): void{
        self::$payload[$key] = $value;
    }

    public static function set($payload): void{
        self::$payload = $payload;
    }

    public static function getAll(): array
    {
        return self::$payload;
    }

    public static function get(string $param, mixed $default = null): mixed
    {
        $value = null;

        if (Assert::keyExists($param, self::$payload)) $value = self::$payload[$param];

        return $value ?? $default;
    }
    public static function clear(): void
    {
        self::$payload = [];
    }
}