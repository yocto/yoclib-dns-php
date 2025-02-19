<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger32;

class SOA extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==7){
            throw new DNSTypeException('Only seven fields allowed.');
        }
        if(!($fields[0] instanceof FQDN)){
            throw new DNSTypeException('First field should be a FQDN.');
        }
        if(!($fields[1] instanceof FQDN)){
            throw new DNSTypeException('Second field should be a FQDN.');
        }
        if(!($fields[2] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Third field should be an UInt32.');
        }
        if(!($fields[3] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Fourth field should be an UInt32.');
        }
        if(!($fields[4] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Fifth field should be an UInt32.');
        }
        if(!($fields[5] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Sixth field should be an UInt32.');
        }
        if(!($fields[6] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Seventh field should be an UInt32.');
        }
    }

}