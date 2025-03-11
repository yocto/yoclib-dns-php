<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class UnsignedInteger48 implements Field{

    private int $value;

    /**
     * @param int $value
     * @throws DNSFieldException
     */
    public function __construct(int $value){
        if($value<0 || $value>281474976710655){
            throw new DNSFieldException('Human readable UInt48 should be in the range of 0 and 281474976710655.');
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
        return substr(pack('J',$this->value),2);
    }

    /**
     * @param string $data
     * @return int
     */
    public static function calculateLength(string $data): int{
        return 6;
    }

    /**
     * @param string $data
     * @return UnsignedInteger48
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): UnsignedInteger48{
        if(!preg_match('/\d+/',$data)){
            throw new DNSFieldException('Human readable UInt48 should only contain digits.');
        }
        return new self(intval($data));
    }

    /**
     * @param string $data
     * @return UnsignedInteger48
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): UnsignedInteger48{
        if(strlen($data)!==6){
            throw new DNSFieldException('Binary UInt48 should be 6 octets.');
        }
        return new self(unpack('J',"\x00\x00".$data)[1]);
    }

}