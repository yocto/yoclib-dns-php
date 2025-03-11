<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger32;

class TKEY extends Type{

    /**
     * @param array|Field[] $fields
     * @throws DNSTypeException
     */
    public function __construct(array $fields){
        parent::__construct($fields);
        if(count($fields)!==7){
            throw new DNSTypeException('Only seven fields allowed.');
        }
        if(!($fields[0] instanceof FQDN)){
            throw new DNSTypeException('First field should be a FQDN.');
        }
        if(!($fields[1] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Second field should be an UInt32.');
        }
        if(!($fields[2] instanceof UnsignedInteger32)){
            throw new DNSTypeException('Third field should be an UInt32.');
        }
        if(!($fields[3] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Fourth field should be an UInt16.');
        }
        if(!($fields[4] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Fifth field should be an UInt16.');
        }
        if(!($fields[5] instanceof Binary)){
            throw new DNSTypeException('Sixth field should be binary.');
        }
        if(!($fields[6] instanceof Binary)){
            throw new DNSTypeException('Seventh field should be binary.');
        }
    }

    /**
     * @return string
     * @throws DNSTypeException
     */
    public function serializeToPresentationFormat(): string{
        throw new DNSTypeException('TKEY doesn\'t have a presentation format to serialize to.');
    }

    /**
     * @return string
     * @throws DNSFieldException
     */
    public function serializeToWireFormat(): string{
        return implode([
            $this->getFields()[0]->serializeToWireFormat(),
            $this->getFields()[1]->serializeToWireFormat(),
            $this->getFields()[2]->serializeToWireFormat(),
            $this->getFields()[3]->serializeToWireFormat(),
            $this->getFields()[4]->serializeToWireFormat(),
            (new UnsignedInteger16(strlen($this->getFields()[5]->getValue())))->serializeToWireFormat(),
            $this->getFields()[5]->serializeToWireFormat(),
            (new UnsignedInteger16(strlen($this->getFields()[6]->getValue())))->serializeToWireFormat(),
            $this->getFields()[6]->serializeToWireFormat(),
        ]);
    }

    /**
     * @param string $data
     * @return TKEY
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): TKEY{
        throw new DNSTypeException('TKEY doesn\'t have a presentation format to deserialize from.');
    }

    /**
     * @param string $data
     * @return TKEY
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): TKEY{
        $offset = 0;

        $algorithm = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($algorithm);

        $inception = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($inception);

        $expiration = substr($data,$offset,UnsignedInteger32::calculateLength(substr($data,$offset)));
        $offset += strlen($expiration);

        $mode = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($mode);

        $error = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($error);

        $keySize = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($keySize);
        $keyData = substr($data,$offset,Binary::calculateLength(substr($data,$offset,UnsignedInteger16::deserializeFromWireFormat($keySize)->getValue())));
        $offset += strlen($keyData);

        $otherSize = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($otherSize);
        $otherData = substr($data,$offset,Binary::calculateLength(substr($data,$offset,UnsignedInteger16::deserializeFromWireFormat($otherSize)->getValue())));
        $offset += strlen($otherData);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            FQDN::deserializeFromWireFormat($algorithm),
            UnsignedInteger32::deserializeFromWireFormat($inception),
            UnsignedInteger32::deserializeFromWireFormat($expiration),
            UnsignedInteger16::deserializeFromWireFormat($mode),
            UnsignedInteger16::deserializeFromWireFormat($error),
            Binary::deserializeFromWireFormat($keyData),
            Binary::deserializeFromWireFormat($otherData),
        ]);
    }

}