<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class CAA extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger8)){
            throw new DNSTypeException('First field should be an UInt8.');
        }
        if(!($fields[1] instanceof CharacterString)){
            throw new DNSTypeException('Second field should be a character string.');
        }
        if(!($fields[2] instanceof Binary)){
            throw new DNSTypeException('Third field should be binary.');
        }
    }

    /**
     * @return string
     * @throws DNSFieldException
     */
    public function serializeToPresentationFormat(): string{
        return implode(' ',[
            $this->getFields()[0]->serializeToPresentationFormat(),
            $this->getFields()[1]->serializeToPresentationFormat(),
            (new CharacterString($this->getFields()[2]->getValue()))->serializeToPresentationFormat(),
        ]);
    }

    /**
     * @param string $data
     * @return CAA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): CAA{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==3){
            throw new DNSTypeException('CAA record should contain 3 fields.');
        }
        return new self([
            UnsignedInteger8::deserializeFromPresentationFormat($tokens[0]),
            CharacterString::deserializeFromPresentationFormat($tokens[1]),
            new Binary(CharacterString::deserializeFromPresentationFormat($tokens[2])->getValue()),
        ]);
    }

    /**
     * @param string $data
     * @return CAA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): CAA{
        $offset = 0;

        $flags = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($flags);

        $tag = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($tag);

        $value = substr($data,$offset);

        return new self([
            UnsignedInteger8::deserializeFromWireFormat($flags),
            CharacterString::deserializeFromWireFormat($tag),
            Binary::deserializeFromWireFormat($value),
        ]);
    }

}