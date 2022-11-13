<?php

namespace CaioMarcatti12\Data\Resolver;

use CaioMarcatti12\Core\Bean\Annotation\AnnotationResolver;
use CaioMarcatti12\Core\Bean\Interfaces\ParameterResolverInterface;
use CaioMarcatti12\Data\Annotation\Header;
use CaioMarcatti12\Data\Request\Objects\Header as HeaderPayload;
use MongoDB\BSON\ObjectId;

#[AnnotationResolver(Header::class)]
class HeaderResolver implements ParameterResolverInterface
{
    public function handler(mixed &$instance, \ReflectionParameter $reflectionParameter): void
    {
        $attributes = $reflectionParameter->getAttributes(Header::class);

        /** @var Header $header */
        $header = $attributes[0]->newInstance();

        $instance = HeaderPayload::get($header->getHeader(), $header->getDefaultValue());

        if($reflectionParameter->getType()->getName() === ObjectId::class){
            $instance = new ObjectId($instance);
        }
    }
}