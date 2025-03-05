<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\IPv6Address;
use YOCLIB\DNS\LineLexer;

class AAAA extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof IPv6Address)){
            throw new DNSTypeException('Field should be an IPv6 address.');
        }
    }

    /**
     * @param string $data
     * @return AAAA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): AAAA{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('AAAA record should contain 1 field.');
        }
        return new self([
            IPv6Address::deserializeFromPresentationFormat($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return AAAA
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): AAAA{
        $offset = 0;

        $address = substr($data,$offset,IPv6Address::calculateLength(substr($data,$offset)));
        $offset += strlen($address);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            IPv6Address::deserializeFromWireFormat($address),
        ]);
    }

}