<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\LineLexer;

class NAPTR extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==6){
            throw new DNSTypeException('Only six fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger16)){
            throw new DNSTypeException('First field should be an UInt16.');
        }
        if(!($fields[1] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Second field should be an UInt16.');
        }
        if(!($fields[2] instanceof CharacterString)){
            throw new DNSTypeException('Third field should be a character string.');
        }
        if(!($fields[3] instanceof CharacterString)){
            throw new DNSTypeException('Fourth field should be a character string.');
        }
        if(!($fields[4] instanceof CharacterString)){
            throw new DNSTypeException('Fifth field should be a character string.');
        }
        if(!($fields[5] instanceof FQDN)){
            throw new DNSTypeException('Sixth field should be a FQDN.');
        }
    }

    /**
     * @param string $data
     * @return NAPTR
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NAPTR{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==6){
            throw new DNSTypeException('NAPTR record should contain 6 fields.');
        }
        return new self([
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[1]),
            CharacterString::deserializeFromPresentationFormat($tokens[2]),
            CharacterString::deserializeFromPresentationFormat($tokens[3]),
            CharacterString::deserializeFromPresentationFormat($tokens[4]),
            FQDN::deserializeFromPresentationFormat($tokens[5]),
        ]);
    }

    /**
     * @param string $data
     * @return NAPTR
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NAPTR{
        $offset = 0;

        $order = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($order);

        $preference = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($preference);

        $flags = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($flags);

        $services = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($services);

        $regExp = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($regExp);

        $replacement = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($replacement);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }

        return new self([
            UnsignedInteger16::deserializeFromWireFormat($order),
            UnsignedInteger16::deserializeFromWireFormat($preference),
            CharacterString::deserializeFromWireFormat($flags),
            CharacterString::deserializeFromWireFormat($services),
            CharacterString::deserializeFromWireFormat($regExp),
            FQDN::deserializeFromWireFormat($replacement),
        ]);
    }

}