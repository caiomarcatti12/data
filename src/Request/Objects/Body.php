<?php

namespace CaioMarcatti12\Data\Request\Objects;


class Body
{
    private static array $payload = [];

    public static function add($key, $value): void{
        self::$payload[$key] = $value;
    }

    public static function set(array $payload): void{
        self::$payload = $payload;
    }

    public static function getAll(): array
    {
        return self::$payload;
    }

    public static function get(string $param, mixed $default = null): mixed
    {
        if(!isset(self::$payload[$param])) return $default;

        return self::$payload[$param];
    }

    public static function size(): int
    {
        return mb_strlen(json_encode(self::$payload));
    }

    public static function clear(): void
    {
        self::$payload = [];
    }
}