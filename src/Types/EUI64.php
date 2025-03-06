<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\ExtendedUniqueIdentifier64;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\LineLexer;

class EUI64 extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==1){
            throw new DNSTypeException('Only one field allowed.');
        }
        if(!($fields[0] instanceof ExtendedUniqueIdentifier64)){
            throw new DNSTypeException('Field should be an EUI64 address.');
        }
    }

    /**
     * @param string $data
     * @return EUI64
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): EUI64{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)!==1){
            throw new DNSTypeException('EUI64 record should contain 1 field.');
        }
        return new self([
            ExtendedUniqueIdentifier64::deserializeFromPresentationFormat($tokens[0]),
        ]);
    }

    /**
     * @param string $data
     * @return EUI64
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): EUI64{
        $offset = 0;

        $address = substr($data,$offset,ExtendedUniqueIdentifier64::calculateLength(substr($data,$offset)));
        $offset += strlen($address);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            ExtendedUniqueIdentifier64::deserializeFromWireFormat($address),
        ]);
    }

}