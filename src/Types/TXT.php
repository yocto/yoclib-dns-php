<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\Field;

class TXT extends Type{

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

    public static function deserializeFromPresentationFormat(string $data): TXT{
        throw new \RuntimeException('Not implemented');
    }

    public static function deserializeFromWireFormat(string $data): TXT{
        throw new \RuntimeException('Not implemented');
    }

}