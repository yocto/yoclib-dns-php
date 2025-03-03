<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class X25 extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof CharacterString)){
            throw new DNSTypeException('First field should be a character string.');
        }
    }

    /**
     * @param string $data
     * @return X25
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): X25{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('X25 record should contain 1 field.');
        }
        return new self([
            CharacterString::deserializeFromPresentationFormat($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return X25
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): X25{
        $offset = 0;

        $psdnAddress = substr($data,$offset,CharacterString::calculateLength(substr($data,$offset)));
        $offset += strlen($psdnAddress);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            CharacterString::deserializeFromWireFormat($psdnAddress),
        ]);
    }

}