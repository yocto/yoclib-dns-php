<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class ISDN extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)<1 || count($fields)>2){
            throw new DNSTypeException('Only one or two fields allowed.');
        }
        if(!($fields[0] instanceof CharacterString)){
            throw new DNSTypeException('First field should be a character string.');
        }
        if(count($fields)==2 && !($fields[1] instanceof CharacterString)){
            throw new DNSTypeException('Second field should be a character string.');
        }
    }

    /**
     * @param string $data
     * @return ISDN
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): ISDN{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<1 || count($tokens)>2){
            throw new DNSTypeException('ISDN record should contain 1 or 2 fields.');
        }
        if(count($tokens)==2){
            return new self([
                CharacterString::deserializeFromPresentationFormat($tokens[0]),
                CharacterString::deserializeFromPresentationFormat($tokens[1]),
            ]);
        }
        return new self([
            CharacterString::deserializeFromPresentationFormat($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return ISDN
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): ISDN{
        $offset = 0;

        $isdnAddress = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($isdnAddress);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            $sa = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
            $offset += strlen($sa);

            $remaining = substr($data,$offset);
            if(strlen($remaining)>0){
                throw new DNSTypeException('Cannot have remaining data.');
            }
            return new self([
                CharacterString::deserializeFromWireFormat($isdnAddress),
                CharacterString::deserializeFromWireFormat($sa),
            ]);
        }
        return new self([
            CharacterString::deserializeFromWireFormat($isdnAddress),
        ]);
    }

}