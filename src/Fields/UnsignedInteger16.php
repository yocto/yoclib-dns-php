<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class UnsignedInteger16 implements Field{

    private int $value;

    /**
     * @param int $value
     * @throws DNSFieldException
     */
    public function __construct(int $value){
        if($value<0 || $value>65535){
            throw new DNSFieldException("Human readable UInt16 should be in the range of 0 and 65535.");
        }
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int{
        return $this->value;
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return strval($this->value);
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return pack('n',$this->value);
    }

    /**
     * @param string $data
     * @return UnsignedInteger16
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): UnsignedInteger16{
        if(!preg_match('/\d+/',$data)){
            throw new DNSFieldException("Human readable UInt16 should only contain digits.");
        }
        return new self(intval($data));
    }

    /**
     * @param string $data
     * @return UnsignedInteger16
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): UnsignedInteger16{
        if(strlen($data)!==2){
            throw new DNSFieldException("Binary UInt16 should be 1 octet.");
        }
        return new self(unpack('n',$data)[1]);
    }

}