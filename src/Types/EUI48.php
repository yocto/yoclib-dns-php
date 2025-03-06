<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\ExtendedUniqueIdentifier48;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class EUI48 extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof ExtendedUniqueIdentifier48)){
            throw new DNSTypeException('Field should be an EUI48 address.');
        }
    }

    /**
     * @param string $data
     * @return EUI48
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): EUI48{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('EUI48 record should contain 1 field.');
        }
        return new self([
            ExtendedUniqueIdentifier48::deserializeFromPresentationFormat($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return EUI48
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): EUI48{
        $offset = 0;

        $address = substr($data,$offset,ExtendedUniqueIdentifier48::calculateLength(substr($data,$offset)));
        $offset += strlen($address);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            ExtendedUniqueIdentifier48::deserializeFromWireFormat($address),
        ]);
    }

}