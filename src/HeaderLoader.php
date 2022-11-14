<?php

namespace CaioMarcatti12\Data;

use CaioMarcatti12\Core\Validation\Assert;
use CaioMarcatti12\Data\Request\Objects\Header;

class HeaderLoader
{
    public function __construct()
    {
        Header::clear();
    }

    public function load($data): void{
        $this->parse($data);
    }

    private function parse($data): void{
        if(is_array($data) && Assert::isNotEmpty($data)){
            foreach($data as $key => $value){
                Header::add($key, $value);
            }
        }
    }
}