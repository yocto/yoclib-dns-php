<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\IPv6Address;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class AMTRELAY extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)<2 || count($fields)>3){
            throw new DNSTypeException('Only two or three fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(count($fields)===3 && !($fields[2] instanceof IPv4Address) && !($fields[2] instanceof IPv6Address) && !($fields[2] instanceof FQDN)){
            throw new DNSTypeException('Third field should be an IPv4 address, IPv6 address or a FQDN.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        if(count($this->getFields())===3){
            return implode(' ',[
                $this->getFields()[0]->serializeToPresentationFormat(),
                $this->getFields()[1]->getValue()>>7 & 0b1,
                $this->getFields()[1]->getValue() & 0b1111111,
                $this->getFields()[2]->serializeToPresentationFormat(),
            ]);
        }
        return implode(' ',[
            $this->getFields()[0]->serializeToPresentationFormat(),
            $this->getFields()[1]->getValue()>>7 & 0b1,
            $this->getFields()[1]->getValue() & 0b1111111,
        ]);
    }

    /**
     * @param string $data
     * @return AMTRELAY
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): AMTRELAY{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<3){
            throw new DNSTypeException('AMTRELAY record should contain at least 3 fields.');
        }
        $discoveryBit = UnsignedInteger8::deserializeFromPresentationFormat($tokens[1])->getValue();
        $typeValue = UnsignedInteger8::deserializeFromPresentationFormat($tokens[2])->getValue();
        $relayValue = null;
        switch($typeValue){
            case 0:{
                break;
            }
            case 1:{
                $relayValue = IPv4Address::deserializeFromPresentationFormat($tokens[3]);
                break;
            }
            case 2:{
                $relayValue = IPv6Address::deserializeFromPresentationFormat($tokens[3]);
                break;
            }
            case 3:{
                $relayValue = FQDN::deserializeFromPresentationFormat($tokens[3]);
                break;
            }
            default:{
                throw new DNSTypeException('Invalid type.');
            }
        }
        if($relayValue){
            return new self([
                UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]),
                new UnsignedInteger8(($discoveryBit&0b1)<<7 | ($typeValue&0b1111111)),
                $relayValue
            ]);
        }
        return new self([
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]),
            new UnsignedInteger8(($discoveryBit&0b1)<<7 | ($typeValue&0b1111111)),
        ]);
    }

    /**
     * @param string $data
     * @return AMTRELAY
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): AMTRELAY{
        $offset = 0;

        $precedence = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($precedence);

        $type = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($type);

        $typeValue = UnsignedInteger8::deserializeFromWireFormat($type)->getValue() & 0x7F;
        $relayValue = null;
        switch($typeValue){
            case 0:{
                break;
            }
            case 1:{
                $relay = substr($data,$offset,IPv4Address::calculateLength(substr($data,$offset)));
                $offset += strlen($relay);
                $relayValue = IPv4Address::deserializeFromWireFormat($relay);
                break;
            }
            case 2:{
                $relay = substr($data,$offset,IPv6Address::calculateLength(substr($data,$offset)));
                $offset += strlen($relay);
                $relayValue = IPv6Address::deserializeFromWireFormat($relay);
                break;
            }
            case 3:{
                $relay = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
                $offset += strlen($relay);
                $relayValue = FQDN::deserializeFromWireFormat($relay);
                break;
            }
            default:{
                throw new DNSTypeException('Invalid type.');
            }
        }

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        if($relayValue){
            return new static([
                UnsignedInteger8::deserializeFromWireFormat($precedence),
                UnsignedInteger8::deserializeFromWireFormat($type),
                $relayValue,
            ]);
        }
        return new static([
            UnsignedInteger8::deserializeFromWireFormat($precedence),
            UnsignedInteger8::deserializeFromWireFormat($type),
        ]);
    }

}