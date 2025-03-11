<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class DSYNC extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==4){
            throw new DNSTypeException('Only four fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger16)){
            throw new DNSTypeException('First field should be an UInt16.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Third field should be an UInt16.');
        }
        if(!($fields[3] instanceof FQDN)){
            throw new DNSTypeException('Fourth field should be a FQDN.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return implode(' ',[
            //TODO Add mnemonic serializing
            $this->getFields()[0]->serializeToPresentationFormat(),
            $this->getFields()[1]->serializeToPresentationFormat(),
            $this->getFields()[2]->serializeToPresentationFormat(),
            $this->getFields()[3]->serializeToPresentationFormat(),
        ]);
    }

    /**
     * @param string $data
     * @return DSYNC
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): DSYNC{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==4){
            throw new DNSTypeException('DSYNC record should contain 4 fields.');
        }
        return new self([
            //TODO Add mnemonic deserializing
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[2]),
            FQDN::deserializeFromPresentationFormat($tokens[3]),
        ]);
    }

    /**
     * @param string $data
     * @return DSYNC
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): DSYNC{
        $offset = 0;

        $rrType = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($rrType);

        $scheme = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($scheme);

        $port = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($port);

        $target = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($target);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }

        return new self([
            UnsignedInteger16::deserializeFromWireFormat($rrType),
            UnsignedInteger8::deserializeFromWireFormat($scheme),
            UnsignedInteger16::deserializeFromWireFormat($port),
            FQDN::deserializeFromWireFormat($target),
        ]);
    }

}