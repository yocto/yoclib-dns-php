<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\Option;
use YOCLIB\DNS\LineLexer;

class OPT extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        $totalLength = 0;
        foreach($fields as $field){
            if(!($field instanceof Option)){
                throw new DNSTypeException('Every field should be an option.');
            }
            $totalLength += strlen($field->serializeToWireFormat());
        }
        if($totalLength>65536){
            throw new DNSTypeException('Maximum size exceeded.');
        }
    }

    /**
     * @return string
     * @throws DNSTypeException
     */
    public function serializeToPresentationFormat(): string{
        throw new DNSTypeException('OPT doesn\'t have a presentation format to serialize to.');
    }

    /**
     * @param string $data
     * @return OPT
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): OPT{
        throw new DNSTypeException('OPT doesn\'t have a presentation format to deserialize from.');
    }

    /**
     * @param string $data
     * @return OPT
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): OPT{
        $offset = 0;

        $strings = [];
        while($offset<strlen($data)){
            $string = substr($data,$offset,Option::calculateLength(substr($data,$offset)));
            $strings[] = $string;
            $offset += strlen($string);
        }
        return new self(array_map(static function(string $string){
            return Option::deserializeFromWireFormat($string);
        },$strings));
    }

}