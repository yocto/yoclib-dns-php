<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class ExtendedUniqueIdentifier64 implements Field{

    private string $value;

    /**
     * @param string $value
     * @throws DNSFieldException
     */
    public function __construct(string $value){
        if(!preg_match('/[A-Fa-f0-9]{2}-[A-Fa-f0-9]{2}-[A-Fa-f0-9]{2}-[A-Fa-f0-9]{2}-[A-Fa-f0-9]{2}-[A-Fa-f0-9]{2}-[A-Fa-f0-9]{2}-[A-Fa-f0-9]{2}/',$value)){
            throw new DNSFieldException('A EUI64 should be 8 hexadecimal character pairs, separated by hyphens.');
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string{
        return $this->value;
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return $this->value;
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return hex2bin(str_replace('-','',$this->value));
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
     * @return ExtendedUniqueIdentifier64
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): ExtendedUniqueIdentifier64{
        return new self($data);
    }

    /**
     * @param string $data
     * @return ExtendedUniqueIdentifier64
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): ExtendedUniqueIdentifier64{
        if(strlen($data)!==8){
            throw new DNSFieldException('Binary EUI64 address should be 8 octets.');
        }
        return new self(bin2hex($data[0]).'-'.bin2hex($data[1]).'-'.bin2hex($data[2]).'-'.bin2hex($data[3]).'-'.bin2hex($data[4]).'-'.bin2hex($data[5]).'-'.bin2hex($data[6]).'-'.bin2hex($data[7]));
    }

}