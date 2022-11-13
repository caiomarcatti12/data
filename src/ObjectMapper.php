<?php

namespace CaioMarcatti12\Data;

use ArrayObject;
use CaioMarcatti12\Core\Validation\Assert;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONArray;
use ReflectionProperty;

final class ObjectMapper
{
    private function __construct()
    {
    }

    public static function mapper(mixed $data, string $class): mixed
    {
        if(Assert::isEmpty($data)) return null;

        $reflectionClass = new \ReflectionClass($class);
        $instance = $reflectionClass->newInstanceWithoutConstructor();

        if($instance instanceof ArrayObject && isset($data[0]) && Assert::isNotEmpty($instance->getIteratorClass())){
            return self::mapperOfArrayObject($data, $class);
        }

        /** @var ReflectionProperty $reflectionProperty */
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();
            $type = $reflectionProperty->getType()->getName();

            $value = null;

            if($reflectionProperty->hasDefaultValue())
                $value = $reflectionProperty->getDefaultValue();

            if(isset($data[$name])){
                $value = $data[$name] ?? null;
            }

            if(!Assert::isPrimitiveTypeName($type)){
                $reflectionClass = new \ReflectionClass($type);

                if($reflectionClass->isEnum()){
                    $reflectionEnum = new \ReflectionEnum($type);
                    $value = $reflectionEnum->getCase($value)->getValue();
                }
                else if(is_array($value) && isset($value[0])){
                    $valuesList = [];

                    $instanceType = $reflectionClass->getName();

                    $reflectionClasstType = new \ReflectionClass($type);
                    $instanceConstructedType = $reflectionClasstType->newInstanceWithoutConstructor();

                    if($instanceConstructedType instanceof \ArrayObject){
                        $instanceType = $instanceConstructedType->getIteratorClass();
                    }

                    foreach($value as $valueByIndex){
                        $instanceConstructedType->append(ObjectMapper::mapper($valueByIndex, $instanceType));
                    }

                    $value = $instanceConstructedType;
                }
                else if(Assert::isNotEmpty($value) && $type !== ObjectId::class){
                    $value = ObjectMapper::mapper($value, $type);;
                }
                else if(is_string($value) && $type === ObjectId::class){
                    try{
                        $value = new ObjectId($value);
                    }catch (\Throwable $e){
                        $value = null;
                    }
                }
            }

            if($value instanceof BSONArray && $type !== BSONArray::class){
                $value = json_decode(json_encode( $value->getArrayCopy()), true);
            }

            if(($reflectionProperty->getType()->allowsNull() && $value === null) || Assert::isNotEmpty($value))
                $reflectionProperty->setValue($instance, $value);
        }
        return $instance;
    }

    public static function mapperOfArrayObject(mixed $data, string $class): mixed
    {
        $reflectionClass = new \ReflectionClass($class);
        $instance = $reflectionClass->newInstanceWithoutConstructor();


        foreach($data as $object){
            $value = self::mapper($object, $instance->getIteratorClass());
            $instance->append($value);
        }

        return $instance;
    }


    public static function toArray(mixed $data): array{
        $objectArray = [];

        if(Assert::isEmpty($data)) return [];
        if($data instanceof ArrayObject){
            return self::toArrayOfArrayObject($data);
        }
        if($data instanceof \stdClass){
            return self::toArrayOfStdClass($data);
        }

        $reflectionClass = new \ReflectionClass($data);

        /** @var ReflectionProperty $reflectionProperty */
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $name = $reflectionProperty->getName();
            $type = $reflectionProperty->getType()->getName();
            $value = null;

            if($reflectionProperty->isInitialized($data))
                $value = $reflectionProperty->getValue($data);

            if(Assert::isEmpty($value) && $reflectionProperty->hasDefaultValue())
                $value = $reflectionProperty->getDefaultValue();

            if(Assert::isPrimitiveTypeName($type) && !Assert::isPrimitiveTypeName(gettype($value)) && $value !== null){
                $type = get_class($value);
            }

            if($type == ObjectId::class){
                $value = (string) $value;
            }
            else if($value instanceof ArrayObject){
                $newValueList = [];

                foreach ($value as $item) {
                    $newValueList[] = self::toArray($item);
                }

                $value = $newValueList;
            }
            elseif(!Assert::isPrimitiveTypeName($type)){
                $reflectionClass = new \ReflectionClass($type);

                if($reflectionClass->isEnum()){
                    $value = $value->name;
                }else{
                    $value = self::toArray($value);
                }
            }
            // else if(!Assert::isPrimitiveTypeName($type)) 

            $objectArray[$name] = $value;
        }

        return $objectArray;
    }

    public static function toArrayOfArrayObject(mixed $data): array{
        $arrayList = [];

        foreach($data as $object){
            $arrayList[] = self::toArray($object);
        }


        return $arrayList;
    }

    public static function toArrayOfStdClass(mixed $data): array{
        $arrayList = [];

        if($data instanceof \stdClass){
            $data = (array) $data;
        }

        foreach($data as $key => $value){
            if($value instanceof \stdClass){
                $arrayList[$key] = self::toArrayOfStdClass($value);
            }else{
                $arrayList[$key] = $value;
            }
        }

        return $arrayList;
    }

    public static function convert(mixed $data, string $class): mixed{
        $arrayData = self::toArray($data);

        return self::mapper($arrayData, $class);
    }
}
