<?php

namespace CaioMarcatti12\Data;

use CaioMarcatti12\Cli\Interfaces\ArgvParserInterface;
use CaioMarcatti12\Data\Request\Objects\Header;
use CaioMarcatti12\Core\Factory\Annotation\Autowired;
use CaioMarcatti12\Core\Validation\Assert;

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