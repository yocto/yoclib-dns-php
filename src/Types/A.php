<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\DNSClass;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\LineLexer;

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

    /**
     * @param string $data
     * @return A
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): A{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('A record should contain 1 field.');
        }
        return new self([
            IPv4Address::deserializeFromPresentationFormat($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return A
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): A{
        $offset = 0;
        $address = substr($data,$offset,IPv4Address::calculateLength($data));
        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            IPv4Address::deserializeFromWireFormat($address),
        ]);
    }

}