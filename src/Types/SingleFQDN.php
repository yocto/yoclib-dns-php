<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;

class SingleFQDN extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof FQDN)){
            throw new DNSTypeException('Field should be a FQDN.');
        }
    }

    public static function deserializeFromPresentationFormat(string $data): self{
        throw new \RuntimeException('Not implemented');
    }

    public static function deserializeFromWireFormat(string $data): self{
        throw new \RuntimeException('Not implemented');
    }

}