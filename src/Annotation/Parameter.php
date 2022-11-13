<?php

namespace CaioMarcatti12\Data\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Parameter
{
    private string $parameter;
    private mixed $defaultValue;

    public function __construct(string $parameter, mixed $defaultValue = null)
    {
        $this->parameter = $parameter;
        $this->defaultValue = $defaultValue;
    }

    public function getParameter(): string
    {
        return $this->parameter;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }
}