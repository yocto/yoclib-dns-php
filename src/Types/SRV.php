<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;

class SRV extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==4){
            throw new DNSTypeException('Only four fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger16)){
            throw new DNSTypeException('First field should be an UInt16.');
        }
        if(!($fields[1] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Second field should be an UInt16.');
        }
        if(!($fields[2] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Third field should be an UInt16.');
        }
        if(!($fields[3] instanceof FQDN)){
            throw new DNSTypeException('Fourth field should be a FQDN.');
        }
    }

}