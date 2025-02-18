<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSConverterException;

class UnsignedInteger8 implements Field{

    private string $value;

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return ''.ord($this->value);
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
       return $this->value;
    }

    /**
     * @param string $data
     * @return UnsignedInteger8
     * @throws DNSConverterException
     */
    public static function deserializeFromPresentationFormat(string $data): UnsignedInteger8{
        $integer = intval($data);
        if($integer<0 || $integer>255){
            throw new DNSConverterException("Human readable UInt8 should be in the range of 0 and 255.");
        }
        $obj = new self;
        $obj->value = chr($integer);
        return $obj;
    }

    /**
     * @param string $data
     * @return UnsignedInteger8
     * @throws DNSConverterException
     */
    public static function deserializeFromWireFormat(string $data): UnsignedInteger8{
        if(strlen($data)!==1){
            throw new DNSConverterException("Binary UInt8 should be 1 octet.");
        }
        $obj = new self;
        $obj->value = $data;
        return $obj;
    }

}