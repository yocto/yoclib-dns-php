<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\LineLexer;

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

    /**
     * @param string $data
     * @return self
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): self{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('Record should contain 1 field.');
        }
        return new static([
            FQDN::deserializeFromPresentationFormat($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return self
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): self{
        $offset = 0;

        $name = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($name);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new static([
            FQDN::deserializeFromWireFormat($name),
        ]);
    }

}