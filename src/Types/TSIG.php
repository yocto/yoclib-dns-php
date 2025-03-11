<?php
namespace YOCLIB\DNS\Types;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Binary;
use YOCLIB\DNS\Fields\Field;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\UnsignedInteger16;
use YOCLIB\DNS\Fields\UnsignedInteger48;

class TSIG extends Type{

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
        if(!($fields[1] instanceof UnsignedInteger48)){
            throw new DNSTypeException('Second field should be an UInt48.');
        }
        if(!($fields[2] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Third field should be an UInt16.');
        }
        if(!($fields[3] instanceof Binary)){
            throw new DNSTypeException('Fourth field should be binary.');
        }
        if(!($fields[4] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Fifth field should be an UInt16.');
        }
        if(!($fields[5] instanceof UnsignedInteger16)){
            throw new DNSTypeException('Sixth field should be an UInt16.');
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
        throw new DNSTypeException('TSIG doesn\'t have a presentation format to serialize to.');
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
            (new UnsignedInteger16(strlen($this->getFields()[3]->getValue())))->serializeToWireFormat(),
            $this->getFields()[4]->serializeToWireFormat(),
            $this->getFields()[5]->serializeToWireFormat(),
            $this->getFields()[6]->serializeToWireFormat(),
            (new UnsignedInteger16(strlen($this->getFields()[7]->getValue())))->serializeToWireFormat(),
            $this->getFields()[8]->serializeToWireFormat(),
        ]);
    }

    /**
     * @param string $data
     * @return TSIG
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormat(string $data): TSIG{
        throw new DNSTypeException('TSIG doesn\'t have a presentation format to deserialize from.');
    }

    /**
     * @param string $data
     * @return TSIG
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormat(string $data): TSIG{
        $offset = 0;

        $algorithm = substr($data,$offset,FQDN::calculateLength(substr($data,$offset)));
        $offset += strlen($algorithm);

        $timeSigned = substr($data,$offset,UnsignedInteger48::calculateLength(substr($data,$offset)));
        $offset += strlen($timeSigned);

        $fudge = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($fudge);

        $macSize = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($macSize);
        $mac = substr($data,$offset,Binary::calculateLength(substr($data,$offset,UnsignedInteger16::deserializeFromWireFormat($macSize)->getValue())));
        $offset += strlen($mac);

        $originalID = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($originalID);

        $error = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($error);

        $otherLength = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($otherLength);
        $otherData = substr($data,$offset,Binary::calculateLength(substr($data,$offset,UnsignedInteger16::deserializeFromWireFormat($otherLength)->getValue())));
        $offset += strlen($otherData);

        $remaining = substr($data,$offset);
        if(strlen($remaining)>0){
            throw new DNSTypeException('Cannot have remaining data.');
        }
        return new self([
            FQDN::deserializeFromWireFormat($algorithm),
            UnsignedInteger48::deserializeFromWireFormat($timeSigned),
            UnsignedInteger16::deserializeFromWireFormat($fudge),
            Binary::deserializeFromWireFormat($mac),
            UnsignedInteger16::deserializeFromWireFormat($originalID),
            UnsignedInteger16::deserializeFromWireFormat($error),
            Binary::deserializeFromWireFormat($otherData),
        ]);
    }

}