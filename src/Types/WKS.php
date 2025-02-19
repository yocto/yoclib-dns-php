<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;

class WKS extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof IPv4Address)){
            throw new DNSTypeException('First field should be a IPv4 address.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof Bitmap)){
            throw new DNSTypeException('Third field should be a bitmap.');
        }
    }

    protected function getMapping(): array{
        return [
            25 => 'SMTP',
        ];
    }

}