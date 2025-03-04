<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class CLA extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)<1){
            throw new DNSTypeException('At least one field required.');
        }
        $totalLength = 0;
        foreach($fields as $field){
            if(!($field instanceof CharacterString)){
                throw new DNSTypeException('Every field should be a character string.');
            }
            $totalLength += strlen($field->serializeToWireFormat());
        }
        if($totalLength>65536){
            throw new DNSTypeException('Maximum size exceeded.');
        }
    }

    /**
     * @param string $data
     * @return CLA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): CLA{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<1){
            throw new DNSTypeException('A CLA record should have at least one character string.');
        }
        return new self(array_map(static function(string $token){
            return CharacterString::deserializeFromPresentationFormat($token);
        },$tokens));
    }

    /**
     * @param string $data
     * @return CLA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): CLA{
        $offset = 0;

        $strings = [];
        while($offset<strlen($data)){
            $string = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
            $strings[] = $string;
            $offset += strlen($string);
        }
        return new self(array_map(static function(string $string){
            return CharacterString::deserializeFromWireFormat($string);
        },$strings));
    }

}