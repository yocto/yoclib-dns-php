<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSConverterException;

class UnsignedInteger16 implements Field{

    private string $value;

    public function serializeToPresentationFormat(): string{
        return ''.unpack('n',$this->value)[1];
    }

    public function serializeToWireFormat(): string{
       return $this->value;
    }

    /**
     * @param string $data
     * @return UnsignedInteger16
     * @throws DNSConverterException
     */
    public static function deserializeFromPresentationFormat(string $data): UnsignedInteger16{
        $integer = intval($data);
        if($integer<0 || $integer>65535){
            throw new DNSConverterException("Human readable UInt16 should be in the range of 0 and 65535.");
        }
        $obj = new self;
        $obj->value = pack('n',$integer);
        return $obj;
    }

    /**
     * @param string $data
     * @return UnsignedInteger16
     * @throws DNSConverterException
     */
    public static function deserializeFromWireFormat(string $data): UnsignedInteger16{
        if(strlen($data)!==2){
            throw new DNSConverterException("Binary UInt16 should be 1 octet.");
        }
        $obj = new self;
        $obj->value = $data;
        return $obj;
    }

}