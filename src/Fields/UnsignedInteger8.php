<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class UnsignedInteger8 implements Field{

    private int $value;

    /**
     * @param int $value
     * @throws DNSFieldException
     */
    public function __construct(int $value){
        if($value<0 || $value>255){
            throw new DNSFieldException('Human readable UInt8 should be in the range of 0 and 255.');
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
       return chr($this->value);
    }

    /**
     * @param string $data
     * @return UnsignedInteger8
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): UnsignedInteger8{
        if(!preg_match('/\d+/',$data)){
            throw new DNSFieldException('Human readable UInt8 should only contain digits.');
        }
        return new self(intval($data));
    }

    /**
     * @param string $data
     * @return UnsignedInteger8
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): UnsignedInteger8{
        if(strlen($data)!==1){
            throw new DNSFieldException('Binary UInt8 should be 1 octet.');
        }
        return new self(ord($data));
    }

}