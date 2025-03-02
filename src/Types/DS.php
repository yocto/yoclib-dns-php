<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class DS extends Type{

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
        if(!($fields[2] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Third field should be an UInt8.');
        }
        if(!($fields[3] instanceof Binary)){
            throw new DNSTypeException('Fourth field should be binary.');
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
            strtoupper(bin2hex($this->getFields()[3]->getValue())),
        ]);
    }

    /**
     * @param string $data
     * @return DS
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): DS{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<4){
            throw new DNSTypeException('A DS record should contain at least 4 fields.');
        }
        $output = '';
        for($i=3;$i<count($tokens);$i++){
            $token = $tokens[$i];
            if(strlen($token)%2!==0){
                throw new DNSTypeException('Every part of hexadecimal data should come in pairs of two.');
            }
            $output .= hex2bin($token);
        }
        return new self([
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[2]),
            new Binary($output),
        ]);
    }

    /**
     * @param string $data
     * @return DS
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): DS{
        $offset = 0;

        $keyTag = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($keyTag);

        $algorithm = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($algorithm);

        $digestType = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($digestType);

        $digest = substr($data,$offset);

        return new self([
            UnsignedInteger16::deserializeFromWireFormat($keyTag),
            UnsignedInteger8::deserializeFromWireFormat($algorithm),
            UnsignedInteger8::deserializeFromWireFormat($digestType),
            Binary::deserializeFromWireFormat($digest),
        ]);
    }

}