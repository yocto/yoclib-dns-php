<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class UnsignedInteger64 implements Field{

    private int $value;

    /**
     * @param int $value
     */
    public function __construct(int $value){
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
        return pack('J',$this->value);
    }

    /**
     * @param string $data
     * @return int
     */
    public static function calculateLength(string $data): int{
        return 8;
    }

    /**
     * @param string $data
     * @return UnsignedInteger64
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): UnsignedInteger64{
        if(!preg_match('/\d+/',$data)){
            throw new DNSFieldException('Human readable UInt64 should only contain digits.');
        }
        return new self(intval($data));
    }

    /**
     * @param string $data
     * @return UnsignedInteger64
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): UnsignedInteger64{
        if(strlen($data)!==8){
            throw new DNSFieldException('Binary UInt64 should be 8 octets.');
        }
        return new self(unpack('J',$data)[1]);
    }

}