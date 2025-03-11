<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv6Address;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class A6 extends Type{

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
        if(!($fields[1] instanceof IPv6Address)){
            throw new DNSTypeException('Second should be an IPv6 address.');
        }
        if(count($fields)==3 && !($fields[2] instanceof FQDN)){
            throw new DNSTypeException('Third field should be a FQDN.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        if($this->getFields()[0]->getValue()===0){
            return implode([
                $this->getFields()[0]->serializeToPresentationFormat(),
                $this->getFields()[1]->serializeToPresentationFormat(),
            ]);
        }
        if($this->getFields()[0]->getValue()===128){
            return implode([
                $this->getFields()[0]->serializeToPresentationFormat(),
                $this->getFields()[2]->serializeToPresentationFormat(),
            ]);
        }
        return parent::serializeToPresentationFormat();
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        if($this->getFields()[0]->getValue()===0){
            return implode([
                $this->getFields()[0]->serializeToWireFormat(),
                substr($this->getFields()[1]->serializeToWireFormat(),16-(ceil(128-$this->getFields()[0]->getValue())/8)),
            ]);
        }
        if($this->getFields()[0]->getValue()===128){
            return implode([
                $this->getFields()[0]->serializeToWireFormat(),
                $this->getFields()[2]->serializeToWireFormat(),
            ]);
        }
        return implode([
            $this->getFields()[0]->serializeToWireFormat(),
            substr($this->getFields()[1]->serializeToWireFormat(),16-ceil(128-$this->getFields()[0]->getValue()/8)),
            $this->getFields()[2]->serializeToWireFormat(),
        ]);
    }

    /**
     * @param string $data
     * @return A6
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): A6{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<2 || count($tokens)>3){
            throw new DNSTypeException('A6 record should contain at least 2 fields or at most 3 fields.');
        }
        $prefix = UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]);
        if(count($tokens)==3){
            if($prefix->getValue()===0){
                throw new DNSTypeException('FQDN must be absent when prefix is zero.');
            }
            return new self([
                $prefix,
                IPv6Address::deserializeFromPresentationFormat($tokens[1]),
                FQDN::deserializeFromPresentationFormat($tokens[2]),
            ]);
        }
        if($prefix->getValue()===0){
            return new self([
                $prefix,
                IPv6Address::deserializeFromPresentationFormat($tokens[1]),
            ]);
        }
        return new self([
            $prefix,
            IPv6Address::deserializeFromPresentationFormat('::'),
            FQDN::deserializeFromPresentationFormat($tokens[1]),
        ]);
    }

    /**
     * @param string $data
     * @return A6
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): A6{
        $offset = 0;

        $prefix = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($prefix);

        $addressSuffixLength = ceil(128-UnsignedInteger8::deserializeFromWireFormat($prefix)->getValue())/8;
        $addressSuffix = substr($data,$offset,$addressSuffixLength);
        $offset += strlen($addressSuffix);
        $addressSuffix = str_pad($addressSuffix,16,"\x00",STR_PAD_LEFT);

        $remaining = substr($data,$offset);
        $prefixName = null;
        if(strlen($remaining)>0){
            $prefixName = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
            $offset += strlen($prefixName);
        }

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }

        if($prefixName){
            return new self([
                UnsignedInteger8::deserializeFromWireFormat($prefix),
                IPv6Address::deserializeFromWireFormat($addressSuffix),
                FQDN::deserializeFromWireFormat($prefixName),
            ]);
        }
        return new self([
            UnsignedInteger8::deserializeFromWireFormat($prefix),
            IPv6Address::deserializeFromWireFormat($addressSuffix),
        ]);
    }

}