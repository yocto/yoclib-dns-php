<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class IPv4Address implements Field{

    private string $value;

    /**
     * @param string $value
     * @throws DNSFieldException
     */
    public function __construct(string $value){
        if(!filter_var($value,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
            throw new DNSFieldException("Human readable IPv4 address should have 4 unsigned integers ranging from 0 to 255, all seperated by dot.");
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
        return inet_pton($this->value);
    }

    /**
     * @param string $data
     * @return IPv4Address
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): IPv4Address{
        return new self($data);
    }

    /**
     * @param string $data
     * @return IPv4Address
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): IPv4Address{
        if(strlen($data)!==4){
            throw new DNSFieldException("Binary IPv4 address should be 4 octets.");
        }
        return new self(inet_ntop($data));
    }

}