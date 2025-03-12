<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class NSEC3PARAM extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==4){
            throw new DNSTypeException('Only four fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Third field should be an UInt16.');
        }
        if(!($fields[3] instanceof CharacterString)){
            throw new DNSTypeException('Fourth field should be a character string.');
        }
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return implode(' ',[
            $this->getFields()[0]->serializeToPresentationFormat(),
            $this->getFields()[1]->serializeToPresentationFormat(),
            $this->getFields()[2]->serializeToPresentationFormat(),
            $this->getFields()[3]->getValue()===''?'-':strtoupper(bin2hex($this->getFields()[3]->getValue())),
        ]);
    }

    /**
     * @param string $data
     * @return NSEC3PARAM
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): NSEC3PARAM{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==4){
            throw new DNSTypeException('NSEC3PARAM record should contain 4 fields.');
        }
        if($tokens[3]!=='-' && !preg_match('/([A-Fa-f0-9]{2})+/',$tokens[3])){
            throw new DNSTypeException('Salt should be a hexadecimal string or a hyphen.');
        }
        return new self([
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[2]),
            new CharacterString($tokens[3]==='-'?'':hex2bin($tokens[3])),
        ]);
    }

    /**
     * @param string $data
     * @return NSEC3PARAM
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): NSEC3PARAM{
        $offset = 0;

        $hashAlgorithm = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($hashAlgorithm);

        $flags = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($flags);

        $iterations = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($iterations);

        $salt = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($salt);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            UnsignedInteger8::deserializeFromWireFormat($hashAlgorithm),
            UnsignedInteger8::deserializeFromWireFormat($flags),
            UnsignedInteger16::deserializeFromWireFormat($iterations),
            CharacterString::deserializeFromWireFormat($salt),
        ]);
    }

}