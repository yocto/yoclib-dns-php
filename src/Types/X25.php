<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;

class X25 extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof CharacterString)){
            throw new DNSTypeException('Field should be a character string.');
        }
    }

    public static function deserializeFromPresentationFormat(string $data): X25{
        throw new \RuntimeException('Not implemented');
    }

    public static function deserializeFromWireFormat(string $data): X25{
        throw new \RuntimeException('Not implemented');
    }

}