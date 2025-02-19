<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;

class HINFO extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==2){
            throw new DNSTypeException('Only two fields allowed.');
        }
        if(!($fields[0] instanceof CharacterString)){
            throw new DNSTypeException('First field should be a character string.');
        }
        if(!($fields[1] instanceof CharacterString)){
            throw new DNSTypeException('Second field should be a character string.');
        }
    }

}