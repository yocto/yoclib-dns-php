<?php
namespace YOCLIB\DNS\Fields;

use GMP;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class UnsignedInteger64 implements Field{

    private GMP $value;

    /**
     * @param int|GMP $value
     * @throws DNSFieldException
     */
    public function __construct(int|GMP $value){
        if(gmp_cmp($value,0)<0 || gmp_cmp($value,'18446744073709551615')>0){
            throw new DNSFieldException('Human readable UInt64 should be in the range of 0 and 18446744073709551615.');
        }
        if(is_int($value)){
            $value = new GMP($value);
        }
        $this->value = $value;
    }

    /**
     * @return GMP
     */
    public function getValue(): GMP{
        return $this->value;
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return gmp_strval($this->value);
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        if(gmp_cmp($this->value,0)===0){
            return "\x00\x00\x00\x00\x00\x00\x00\x00";
        }
        return gmp_export($this->value,8,GMP_BIG_ENDIAN);
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
        return new self(new GMP($data));
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
        return new self(gmp_import($data,8,GMP_BIG_ENDIAN));
    }

}