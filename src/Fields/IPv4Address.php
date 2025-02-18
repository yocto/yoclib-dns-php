<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSConverterException;

class IPv4Address implements Field{

    private string $value;

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return inet_ntop($this->value);
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return $this->value;
    }

    /**
     * @param string $data
     * @return IPv4Address
     * @throws DNSConverterException
     */
    public static function deserializeFromPresentationFormat(string $data): IPv4Address{
        if(!filter_var($data,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
            throw new DNSConverterException("Human readable IPv4 address should have 4 unsigned integers ranging from 0 to 255, all seperated by dot.");
        }
        $obj = new self;
        $obj->value = inet_pton($data);
        return $obj;
    }

    /**
     * @param string $data
     * @return IPv4Address
     * @throws DNSConverterException
     */
    public static function deserializeFromWireFormat(string $data): IPv4Address{
        if(strlen($data)!==4){
            throw new DNSConverterException("Binary IPv4 address should be 4 octets.");
        }
        $obj = new self;
        $obj->value = $data;
        return $obj;
    }

}