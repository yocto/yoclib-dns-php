<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class DOA extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==5){
            throw new DNSTypeException('Only four fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger32)){
            throw new DNSTypeException('First field should be an UInt32.');
        }
        if(!($fields[1] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Second field should be an UInt32.');
        }
        if(!($fields[2] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Third field should be an UInt8.');
        }
        if(!($fields[3] instanceof CharacterString)){
            throw new DNSTypeException('Fourth field should be a character string.');
        }
        if(!($fields[4] instanceof Binary)){
            throw new DNSTypeException('Fifth field should be binary.');
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
            $this->getFields()[3]->serializeToPresentationFormat(),
            base64_encode($this->getFields()[4]->getValue()),
        ]);
    }

    /**
     * @param string $data
     * @return DOA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): DOA{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<4){
            throw new DNSTypeException('DOA record should contain at least 4 fields.');
        }
        $output = '';
        for($i=4;$i<count($tokens);$i++){
            $token = $tokens[$i];
            $output .= $token;
        }
        return new self([
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger32::deserializeFromPresentationFormat($tokens[1]),
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[2]),
            CharacterString::deserializeFromPresentationFormat($tokens[3]),
            new Binary(base64_decode($output)),
        ]);
    }

    /**
     * @param string $data
     * @return DOA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): DOA{
        $offset = 0;

        $enterprise = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($enterprise);

        $type = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($type);

        $location = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($location);

        $mediaType = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($mediaType);

        $remainingData = substr($data,$offset);

        return new self([
            UnsignedInteger32::deserializeFromWireFormat($enterprise),
            UnsignedInteger32::deserializeFromWireFormat($type),
            UnsignedInteger8::deserializeFromWireFormat($location),
            CharacterString::deserializeFromWireFormat($mediaType),
            Binary::deserializeFromWireFormat($remainingData),
        ]);
    }

}