<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSConverterException;

class IPv6Address implements Field{

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
     * @return Field
     * @throws DNSConverterException
     */
    public static function deserializeFromPresentationFormat(string $data): IPv6Address{
        if(!filter_var($data,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)){
            throw new DNSConverterException("Human readable IPv6 address isn't valid.");
        }
        $obj = new self;
        $obj->value = inet_pton($data);
        return $obj;
    }

    /**
     * @param string $data
     * @return Field
     * @throws DNSConverterException
     */
    public static function deserializeFromWireFormat(string $data): IPv6Address{
        if(strlen($data)!==16){
            throw new DNSConverterException("Binary IPv6 address should be 16 octets.");
        }
        $obj = new self;
        $obj->value = $data;
        return $obj;
    }

}