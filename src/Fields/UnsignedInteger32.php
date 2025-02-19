<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class UnsignedInteger32 implements Field{

    private int $value;

    /**
     * @param int $value
     * @throws DNSFieldException
     */
    public function __construct(int $value){
        if($value<0 || $value>4294967295){
            throw new DNSFieldException('Human readable UInt32 should be in the range of 0 and 4294967295.');
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
        return pack('N',$this->value);
    }

    /**
     * @param string $data
     * @return UnsignedInteger32
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): UnsignedInteger32{
        if(!preg_match('/\d+/',$data)){
            throw new DNSFieldException('Human readable UInt32 should only contain digits.');
        }
        return new self(intval($data));
    }

    /**
     * @param string $data
     * @return UnsignedInteger32
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): UnsignedInteger32{
        if(strlen($data)!==4){
            throw new DNSFieldException('Binary UInt32 should be 4 octets.');
        }
        return new self(unpack('N',$data)[1]);
    }

}