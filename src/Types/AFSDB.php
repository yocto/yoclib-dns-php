<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\LineLexer;

class AFSDB extends Type{

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
        if(!($fields[1] instanceof FQDN)){
            throw new DNSTypeException('Second field should be a FQDN.');
        }
    }

    /**
     * @param string $data
     * @return AFSDB
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): AFSDB{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==2){
            throw new DNSTypeException('AFSDB record should contain 2 fields.');
        }
        return new self([
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            FQDN::deserializeFromPresentationFormat($tokens[1]),
        ]);
    }

    /**
     * @param string $data
     * @return AFSDB
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): AFSDB{
        $offset = 0;

        $subtype = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($subtype);

        $hostname = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($hostname);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            UnsignedInteger16::deserializeFromWireFormat($subtype),
            FQDN::deserializeFromWireFormat($hostname),
        ]);
    }

}