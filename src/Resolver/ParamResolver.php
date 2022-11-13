<?php

namespace CaioMarcatti12\Data\Resolver;

use CaioMarcatti12\Core\Bean\Annotation\AnnotationResolver;
use CaioMarcatti12\Core\Bean\Interfaces\ParameterResolverInterface;
use CaioMarcatti12\Data\Annotation\Parameter;
use CaioMarcatti12\Data\Request\Objects\Body;

#[AnnotationResolver(Parameter::class)]
class ParamResolver implements ParameterResolverInterface
{
    public function handler(mixed &$instance, \ReflectionParameter $reflectionParameter): void
    {
        $attributes = $reflectionParameter->getAttributes(Parameter::class);

        /** @var Parameter $param */
        $param = $attributes[0]->newInstance();

        $instance = Body::get($param->getParameter(), $param->getDefaultValue());
    }
}