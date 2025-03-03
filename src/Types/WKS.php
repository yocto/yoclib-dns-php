<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;

class WKS extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==3){
            throw new DNSTypeException('Only three fields allowed.');
        }
        if(!($fields[0] instanceof IPv4Address)){
            throw new DNSTypeException('First field should be a IPv4 address.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof Bitmap)){
            throw new DNSTypeException('Third field should be a bitmap.');
        }
    }

    protected function getMapping(): array{
        if($this->getFields()[1]->getValue()===6){
            return [
                25 => 'SMTP',
            ];
        }
        return [];
    }

    /**
     * @param string $data
     * @param array|string[][]|null $mappings
     * @return WKS
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data,?array $mappings=null): WKS{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<2){
            throw new DNSTypeException('A WKS record should contain at least 2 fields.');
        }
        $protocol = UnsignedInteger8::deserializeFromPresentationFormat($tokens[1]);
        return new self([
            IPv4Address::deserializeFromPresentationFormat($tokens[0]),
            $protocol,
            Bitmap::deserializeFromPresentationFormat(implode(' ',array_slice($tokens,2)),$mappings[$protocol->getValue()] ?? null),
        ]);
    }

    /**
     * @param string $data
     * @return WKS
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): WKS{
        $offset = 0;

        $address = substr($data,$offset,IPv4Address::calculateLength(substr($data,$offset)));
        $offset += strlen($address);

        $protocol = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($protocol);

        $bitmap = substr($data,$offset);

        return new self([
            IPv4Address::deserializeFromWireFormat($address),
            UnsignedInteger8::deserializeFromWireFormat($protocol),
            Bitmap::deserializeFromWireFormat($bitmap),
        ]);
    }

}