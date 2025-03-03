<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class GPOS extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof CharacterString)){
            throw new DNSTypeException('First field should be a character string.');
        }
        if(!($fields[1] instanceof CharacterString)){
            throw new DNSTypeException('Second field should be a character string.');
        }
        if(!($fields[2] instanceof CharacterString)){
            throw new DNSTypeException('Third field should be a character string.');
        }
    }

    /**
     * @param string $data
     * @return GPOS
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): GPOS{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==3){
            throw new DNSTypeException('GPOS record should contain 3 fields.');
        }
        return new self([
            CharacterString::deserializeFromPresentationFormat($tokens[0]),
            CharacterString::deserializeFromPresentationFormat($tokens[1]),
            CharacterString::deserializeFromPresentationFormat($tokens[2]),
        ]);
    }

    /**
     * @param string $data
     * @return GPOS
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): GPOS{
        $offset = 0;

        $longitude = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($longitude);

        $latitude = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($latitude);

        $altitude = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($altitude);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            CharacterString::deserializeFromWireFormat($longitude),
            CharacterString::deserializeFromWireFormat($latitude),
            CharacterString::deserializeFromWireFormat($altitude),
        ]);
    }

}