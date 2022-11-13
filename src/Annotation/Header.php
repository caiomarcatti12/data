<?php

namespace CaioMarcatti12\Data\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Header
{
    private string $header;
    private mixed $defaultValue;

    public function __construct(string $header, mixed $defaultValue = null)
    {
        $this->header = $header;
        $this->defaultValue = $defaultValue;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }
}