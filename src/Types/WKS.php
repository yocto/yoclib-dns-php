<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\LineLexer;
use YOCLIB\DNS\MnemonicMapper;

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
            throw new DNSTypeException('First field should be an IPv4 address.');
        }
        if(!($fields[1] instanceof UnsignedInteger8)){
            throw new DNSTypeException('Second field should be an UInt8.');
        }
        if(!($fields[2] instanceof Bitmap)){
            throw new DNSTypeException('Third field should be a bitmap.');
        }
    }

    /**
     * @return MnemonicMapper
     * @throws DNSMnemonicException
     */
    protected function getMapper(): MnemonicMapper{
        return new MnemonicMapper(MnemonicMapper::MAPPING_PORTS[$this->getFields()[1]->getValue()] ?? []);
    }

    /**
     * @param string $data
     * @return WKS
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): WKS{
        $tokens = LineLexer::tokenizeLine($data);
        if(count($tokens)<2){
            throw new DNSTypeException('WKS record should contain at least 2 fields.');
        }
        $protocolMapper = new MnemonicMapper(MnemonicMapper::MAPPING_PROTOCOLS);
        $protocol = $protocolMapper->deserializeMnemonic($tokens[1]);
        $portMapper = new MnemonicMapper(MnemonicMapper::MAPPING_PORTS[$protocol] ?? []);
        return new self([
            IPv4Address::deserializeFromPresentationFormat($tokens[0]),
            new UnsignedInteger8($protocol),
            Bitmap::deserializeFromPresentationFormat(array_slice($tokens,2),$portMapper),
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