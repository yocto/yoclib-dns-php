<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class IPv6Address implements Field{

    private string $value;

    /**
     * @param string $value
     * @throws DNSFieldException
     */
    public function __construct(string $value){
        if(!filter_var($value,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)){
            throw new DNSFieldException("Human readable IPv6 address isn't valid.");
        }
        $this->value = $value;
    }

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
     * @return IPv6Address
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): IPv6Address{
        return new self($data);
    }

    /**
     * @param string $data
     * @return IPv6Address
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): IPv6Address{
        if(strlen($data)!==16){
            throw new DNSFieldException("Binary IPv6 address should be 16 octets.");
        }
        return new self(inet_ntop($data));
    }

}