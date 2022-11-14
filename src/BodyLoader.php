<?php

namespace CaioMarcatti12\Data;

use CaioMarcatti12\Core\Validation\Assert;
use CaioMarcatti12\Data\Request\Objects\Body;

class BodyLoader
{
    public function __construct()
    {
        Body::clear();
    }

    public function load($data): void{
        $this->parse($data);
    }

    private function parse($data): void{
        if(is_array($data) && Assert::isNotEmpty($data)){
            foreach($data as $key => $value){
                Body::add($key, $value);
            }
        }
    }
}