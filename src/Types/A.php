<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\DNSClass;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\IPv4Address;

class A extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields,$class=DNSClass::IN){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if($class!==DNSClass::IN){
            throw new DNSTypeException('Other classes not supported at the moment.');
        }
        if(!($fields[0] instanceof IPv4Address)){
            throw new DNSTypeException('Field should be an IPv4 address.');
        }
    }

}