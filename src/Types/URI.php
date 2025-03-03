<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\LineLexer;

class URI extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof UnsignedInteger16)){
            throw new DNSTypeException('First field should be an UInt16.');
        }
        if(!($fields[1] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Second field should be an Uint16.');
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
     * @return URI
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): URI{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==3){
            throw new DNSTypeException('URI record should contain 3 fields.');
        }
        return new self([
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[0]),
            UnsignedInteger16::deserializeFromPresentationFormat($tokens[1]),
            new Binary(CharacterString::deserializeFromPresentationFormat($tokens[2])->getValue()),
        ]);
    }

    /**
     * @param string $data
     * @return URI
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): URI{
        $offset = 0;

        $priority = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($priority);

        $weight = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($weight);

        $target = substr($data,$offset);

        return new self([
            UnsignedInteger16::deserializeFromWireFormat($priority),
            UnsignedInteger16::deserializeFromWireFormat($weight),
            Binary::deserializeFromWireFormat($target),
        ]);
    }

}