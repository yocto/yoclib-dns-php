<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;

class Unknown extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof Binary)){
            throw new DNSTypeException('Field should be binary.');
        }
    }

    public static function deserializeFromPresentationFormat(string $data): Unknown{
        throw new \RuntimeException('Not implemented');
    }

    public static function deserializeFromWireFormat(string $data): Unknown{
        throw new \RuntimeException('Not implemented');
    }

}