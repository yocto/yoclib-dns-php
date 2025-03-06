<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class Locator64 implements Field{

    private string $value;

    /**
     * @param string $value
     * @throws DNSFieldException
     */
    public function __construct(string $value){
        if(!preg_match('/[A-Fa-f0-9]{1,4}:[A-Fa-f0-9]{1,4}:[A-Fa-f0-9]{1,4}:[A-Fa-f0-9]{1,4}/',$value)){
            throw new DNSFieldException('A Locator64 should be 4 groups of hexadecimal digits, separated by colons.');
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
        return hex2bin(implode('',array_map(static function(string $group){
            return str_pad($group,4,'0',STR_PAD_LEFT);
        },explode(':',$this->value))));
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
     * @return Locator64
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): Locator64{
        return new self($data);
    }

    /**
     * @param string $data
     * @return Locator64
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): Locator64{
        if(strlen($data)!==8){
            throw new DNSFieldException('Binary Locator64 should be 8 octets.');
        }
        return new self(bin2hex($data[0]).bin2hex($data[1]).':'.bin2hex($data[2]).bin2hex($data[3]).':'.bin2hex($data[4]).bin2hex($data[5]).':'.bin2hex($data[6]).bin2hex($data[7]));
    }

}