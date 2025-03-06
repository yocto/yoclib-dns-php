<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\LineLexer;

class L32 extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==2){
            throw new DNSTypeException('Only two fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger16)){
            throw new DNSTypeException('First field should be an UInt16.');
        }
        if(!($fields[1] instanceof IPv4Address)){
            throw new DNSTypeException('Second field should be a Locator32/IPv4 address.');
        }
    }

    /**
     * @param string $data
     * @return L32
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): L32{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==2){
            throw new DNSTypeException('MX record should contain 2 fields.');
        }
        return new self([
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            IPv4Address::deserializeFromPresentationFormat($tokens[1]),
        ]);
    }

    /**
     * @param string $data
     * @return L32
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): L32{
        $offset = 0;

        $preference = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($preference);

        $locator32 = substr($data,$offset,IPv4Address::calculateLength(substr($data,$offset)));
        $offset += strlen($locator32);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }

        return new self([
            UnsignedInteger16::deserializeFromWireFormat($preference),
            IPv4Address::deserializeFromWireFormat($locator32),
        ]);
    }

}