<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class HINFO extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==2){
            throw new DNSTypeException('Only two fields allowed.');
        }
        if(!($fields[0] instanceof CharacterString)){
            throw new DNSTypeException('First field should be a character string.');
        }
        if(!($fields[1] instanceof CharacterString)){
            throw new DNSTypeException('Second field should be a character string.');
        }
    }

    /**
     * @param string $data
     * @return HINFO
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): HINFO{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==2){
            throw new DNSTypeException('HINFO record should contain 2 fields.');
        }
        return new self([
            CharacterString::deserializeFromPresentationFormat($tokens[0]),
            CharacterString::deserializeFromPresentationFormat($tokens[1]),
        ]);
    }

    /**
     * @param string $data
     * @return HINFO
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): HINFO{
        $offset = 0;

        $cpu = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($cpu);

        $os = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($os);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            CharacterString::deserializeFromWireFormat($cpu),
            CharacterString::deserializeFromWireFormat($os),
        ]);
    }

}