<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\IPv6Address;

class AAAA extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof IPv6Address)){
            throw new DNSTypeException('Field should be an IPv6 address.');
        }
    }

    public static function deserializeFromPresentationFormat(string $data): AAAA{
        throw new \RuntimeException('Not implemented');
    }

    public static function deserializeFromWireFormat(string $data): AAAA{
        throw new \RuntimeException('Not implemented');
    }

}