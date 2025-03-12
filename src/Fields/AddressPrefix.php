<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class AddressPrefix implements Field{

    private array $value;

    /**
     * @param array|string[] $value
     * @throws DNSFieldException
     */
    public function __construct(string... $value){
        if(count($value)!==4){
            throw new DNSFieldException('The value should have 4 elements.');
        }
        $addressFamily = intval($value[0] ?? '');
        if($addressFamily!==1 && $addressFamily!==2){
            throw new DNSFieldException('Only IPv4 and IPv6 address families are supported.');
        }
        $prefix = intval($value[1] ?? '');
        if($prefix<0){
            throw new DNSFieldException('Prefix should be positive.');
        }
        if($addressFamily===1 && $prefix>32){
            throw new DNSFieldException('Prefix cannot be larger than 32 for IPv4 addresses.');
        }
        if($addressFamily===2 && $prefix>128){
            throw new DNSFieldException('Prefix cannot be larger than 128 for IPv6 addresses.');
        }
        $value[2] = boolval($value[2] ?? '');
        $address = $value[3] ?? '';
        if($addressFamily===1 && !filter_var($address,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)){
            throw new DNSFieldException('Human readable IPv4 address should have 4 unsigned integers ranging from 0 to 255, all seperated by dot.');
        }
        if($addressFamily===2 && !filter_var($address,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)){
            throw new DNSFieldException('Human readable IPv6 address isn\'t valid.');
        }
        $this->value = $value;
    }

    /**
     * @return array|string[]
     */
    public function getValue(): array{
        return $this->value;
    }

    /**
     * @return string
     */
    public function serializeToPresentationFormat(): string{
        return ($this->value[2]?'!':'').$this->value[0].':'.$this->value[3].'/'.$this->value[1];
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        $address = rtrim(inet_pton($this->value[3]),"\x00");
        $length = strlen($address);
        if($this->value[2]){
            $length |= 0x80;
        }
        return pack('n',intval($this->value[0])).chr(intval($this->value[1])).chr($length).$address;
    }

    /**
     * @param string $data
     * @return int
     */
    public static function calculateLength(string $data): int{
        return 4+(ord($data[3] ?? "\x00")&0x7F);
    }

    /**
     * @param string $data
     * @return AddressPrefix
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): AddressPrefix{
        if(!preg_match('/^(!?)(\d+):([A-Za-z0-9.:]+)\/(\d+)$/',$data,$matches)){
            throw new DNSFieldException('Invalid address prefix format.');
        }
        return new self($matches[2],$matches[4],$matches[1],$matches[3]);
    }

    /**
     * @param string $data
     * @return AddressPrefix
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): AddressPrefix{
        if(strlen($data)<=4){
            throw new DNSFieldException('Address Prefix should be at least 4 octets.');
        }
        $offset = 0;

        $addressFamily = substr($data,$offset,UnsignedInteger16::calculateLength(substr($data,$offset)));
        $offset += strlen($addressFamily);
        $addressFamilyValue = UnsignedInteger16::deserializeFromWireFormat($addressFamily)->getValue();

        $prefix = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($prefix);
        $prefixValue = UnsignedInteger8::deserializeFromWireFormat($prefix)->getValue();

        $addressLength = substr($data,$offset,UnsignedInteger8::calculateLength(substr($data,$offset)));
        $offset += strlen($addressLength);
        $addressLengthValue = UnsignedInteger8::deserializeFromWireFormat($addressLength)->getValue();

        $negation = ($addressLengthValue>>7)&0b1;

        $address = substr($data,$offset,$addressLengthValue & 0x7F);
        $address = str_pad($address,$addressFamilyValue==1?4:16,"\x00");

        return new self($addressFamilyValue,$prefixValue,$negation,inet_ntop($address));
    }

}