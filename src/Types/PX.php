<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\LineLexer;

class PX extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger16)){
            throw new DNSTypeException('First field should be an UInt16.');
        }
        if(!($fields[1] instanceof FQDN)){
            throw new DNSTypeException('Second field should be a FQDN.');
        }
        if(!($fields[2] instanceof FQDN)){
            throw new DNSTypeException('Third field should be a FQDN.');
        }
    }

    /**
     * @param string $data
     * @return PX
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): PX{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==3){
            throw new DNSTypeException('PX record should contain 3 fields.');
        }
        return new self([
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            FQDN::deserializeFromPresentationFormat($tokens[1]),
            FQDN::deserializeFromPresentationFormat($tokens[2]),
        ]);
    }

    /**
     * @param string $data
     * @return PX
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): PX{
        $offset = 0;

        $preference = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($preference);

        $map822 = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($map822);

        $mapX400 = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($mapX400);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }

        return new self([
            UnsignedInteger16::deserializeFromWireFormat($preference),
            FQDN::deserializeFromWireFormat($map822),
            FQDN::deserializeFromWireFormat($mapX400),
        ]);
    }

}