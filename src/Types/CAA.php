<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger8;

class CAA extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if(!($fields[1] instanceof CharacterString)){
            throw new DNSTypeException('Second field should be a character string.');
        }
        if(!($fields[2] instanceof Binary)){
            throw new DNSTypeException('Third field should be binary.');
        }
    }

    public static function deserializeFromPresentationFormat(string $data): CAA{
        throw new \RuntimeException('Not implemented');
    }

    public static function deserializeFromWireFormat(string $data): CAA{
        throw new \RuntimeException('Not implemented');
    }

}