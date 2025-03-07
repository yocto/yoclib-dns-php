<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger64;
use YOCLIB\DNS\LineLexer;

class IPN extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger64)){
            throw new DNSTypeException('First field should be an UInt64.');
        }
    }

    /**
     * @param string $data
     * @return IPN
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): IPN{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('IPN record should contain 1 field.');
        }
        if(preg_match('/\d+\.\d+/',$tokens[0])){
            $parts = explode('.',$tokens[0]);
            $msb = UnsignedInteger32::deserializeFromPresentationFormat($parts[0])->getValue() & 0xFFFFFFFF;
            $lsb = UnsignedInteger32::deserializeFromPresentationFormat($parts[1])->getValue() & 0xFFFFFFFF;
            return new self([
                new UnsignedInteger64($msb<<32 | $lsb<<0),
            ]);
        }
        return new self([
            UnsignedInteger64::deserializeFromPresentationFormat($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return IPN
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): IPN{
        $offset = 0;

        $integer = substr($data,$offset,UnsignedInteger64::calculateLength(substr($data,$offset)));
        $offset += strlen($integer);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            UnsignedInteger64::deserializeFromWireFormat($integer),
        ]);
    }

}