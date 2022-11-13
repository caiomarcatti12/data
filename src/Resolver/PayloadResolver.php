<?php

namespace CaioMarcatti12\Data\Resolver;

use CaioMarcatti12\Core\Bean\Annotation\AnnotationResolver;
use CaioMarcatti12\Core\Bean\Interfaces\ParameterResolverInterface;
use CaioMarcatti12\Data\Annotation\Payload;
use CaioMarcatti12\Data\ObjectMapper;
use CaioMarcatti12\Data\Request\Objects\Body;
use ReflectionClass;

#[AnnotationResolver(Payload::class)]
class PayloadResolver implements ParameterResolverInterface
{
    public function handler(mixed &$instance, \ReflectionParameter $reflectionParameter): void
    {
        $reflectionClass = new ReflectionClass($instance);
        $instance = ObjectMapper::mapper(Body::getAll(), $reflectionClass->getName());
    }
}