<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;

class MINFO extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==2){
            throw new DNSTypeException('Only two fields allowed.');
        }
        if(!($fields[0] instanceof FQDN)){
            throw new DNSTypeException('First field should be a FQDN.');
        }
        if(!($fields[1] instanceof FQDN)){
            throw new DNSTypeException('Second field should be a FQDN.');
        }
    }

    public static function deserializeFromPresentationFormat(string $data): MINFO{
        throw new \RuntimeException('Not implemented');
    }

    public static function deserializeFromWireFormat(string $data): MINFO{
        throw new \RuntimeException('Not implemented');
    }

}