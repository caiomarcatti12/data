<?php

namespace CaioMarcatti12\Data;

class UUID
{
    public static function v4(): string{
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}