<?php
namespace YOCLIB\DNS\Fields;

use YOCLIB\DNS\Exceptions\DNSFieldException;

class ServiceParameter implements Field{

    public const KEYS = [
        'mandatory'				=> 0,
        'alpn'					=> 1,
        'no-default-alpn'		=> 2,
        'port'					=> 3,
        'ipv4hint'				=> 4,
        'ech'					=> 5,
        'ipv6hint'				=> 6,
        'dohpath'				=> 7,
        'ohttp'					=> 8,
        'tls-supported-groups'	=> 9,
    ];

    private int $valueKey;
    private ?string $valueValue;

    /**
     * @param int $key
     * @param ?string|null $value
     * @throws DNSFieldException
     */
    public function __construct(int $key,?string $value=null){
        if($key<0 || $key>65535){
            throw new DNSFieldException('Service parameter key should be between 0 and 65535.');
        }
        $this->valueKey = $key;
        $this->valueValue = $value;
    }

    /**
     * @return array
     */
    public function getValue(): array{
        return [$this->valueKey,$this->valueValue];
    }

    /**
     * @return string
     * @throws DNSFieldException
     */
    public function serializeToPresentationFormat(): string{
        $key = array_flip(self::KEYS)[$this->valueKey] ?? 'key'.$this->valueKey;
        if($this->valueValue!==null){
            $value = (new CharacterString($this->valueValue))->serializeToPresentationFormat(true);
            return $key.'='.$value;
        }
        return $key;
    }

    /**
     * @return string
     */
    public function serializeToWireFormat(): string{
        return pack('n',$this->valueKey).pack('n',strlen($this->valueValue ?: '')).($this->valueValue ?: '');
    }

    /**
     * @param string $data
     * @return int
     */
    public static function calculateLength(string $data): int{
        return 4 + unpack('n',str_pad(substr($data,2,2),2,"\x00",STR_PAD_LEFT))[1];
    }

    /**
     * @param string $data
     * @return ServiceParameter
     * @throws DNSFieldException
     */
    public static function deserializeFromPresentationFormat(string $data): ServiceParameter{
        $parts = explode('=',$data,2);
        $key = $parts[0];
        if(!preg_match('/^key\d{1,5}$/',$key) && !in_array($key,array_keys(self::KEYS))){
            throw new DNSFieldException('Invalid service parameter key.');
        }
        $key = self::KEYS[$key] ?? intval(substr($key,3));
        $value = $parts[1] ?? null;
        if($value!==null){
            $value = CharacterString::deserializeFromPresentationFormat($value)->getValue();
            return new self($key,$value);
        }
        return new self($key);
    }

    /**
     * @param string $data
     * @return ServiceParameter
     * @throws DNSFieldException
     */
    public static function deserializeFromWireFormat(string $data): ServiceParameter{
        if(strlen($data)<4){
            throw new DNSFieldException('Service parameter should be at least 4 octets.');
        }

        $key = unpack('n',substr($data,0,2))[1];
        $length = unpack('n',substr($data,2,2))[1];

        $value = substr($data,4,$length);
        if(strlen($value)!==$length){
            throw new DNSFieldException('Too less data available to read service parameter.');
        }
        if($length>0){
            return new self($key,$value);
        }
        return new self($key);
    }

}