<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\LineLexer;

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

    /**
     * @param string $data
     * @return SOA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): SOA{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==7){
            throw new DNSTypeException('SOA record should contain 7 fields.');
        }
        return new self([
            FQDN::deserializeFromPresentationFormat($tokens[0]),
            FQDN::deserializeFromPresentationFormat($tokens[1]),
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[2]),
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[3]),
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[4]),
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[5]),
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[6]),
        ]);
    }

    /**
     * @param string $data
     * @return SOA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): SOA{
        $offset = 0;

        $mname = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($mname);

        $rname = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($rname);

        $serial = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($serial);

        $refresh = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($refresh);

        $retry = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($retry);

        $expire = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($expire);

        $minimum = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($minimum);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            FQDN::deserializeFromWireFormat($mname),
            FQDN::deserializeFromWireFormat($rname),
            UnsignedInteger32::deserializeFromWireFormat($serial),
            UnsignedInteger32::deserializeFromWireFormat($refresh),
            UnsignedInteger32::deserializeFromWireFormat($retry),
            UnsignedInteger32::deserializeFromWireFormat($expire),
            UnsignedInteger32::deserializeFromWireFormat($minimum),
        ]);
    }

}