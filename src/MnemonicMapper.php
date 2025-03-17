<?php
namespace YOCLIB\DNS;

use Closure;

use YOCLIB\DNS\Exceptions\DNSMnemonicException;

class MnemonicMapper{

    public const MAPPING_DNS_TYPES = [
        'A' => DNSType::A,
        'NS' => DNSType::NS,
        'MX' => DNSType::MX,
        'SIG' => DNSType::SIG,
        'AAAA' => DNSType::AAAA,
        'NXT' => DNSType::NXT,
        'RRSIG' => DNSType::RRSIG,
        'NSEC' => DNSType::NSEC,
    ];

    public const MAPPING_PORTS = [
        6 => [
            'FTP' => 21,
            'SMTP' => 25,
        ],
        17 => [],
    ];

    public const MAPPING_PROTOCOLS = [
        'TCP' => 6,
        'UDP' => 17,
    ];

    private array $mapping;

    private ?bool $allowInteger;

    private ?Closure $fallbackDeserializer;
    private ?Closure $fallbackSerializer;

    /**
     * @param array|string[] $mapping
     * @param ?bool|null $allowInteger
     * @param ?Closure|null $fallbackDeserializer
     * @param ?Closure|null $fallbackSerializer
     * @throws DNSMnemonicException
     */
    public function __construct(array $mapping,?bool $allowInteger=true,?Closure $fallbackDeserializer=null,?Closure $fallbackSerializer=null){
        foreach($mapping as $mnemonic=>$value){
            if(!is_string($mnemonic)){
                throw new DNSMnemonicException("All mapping keys should be strings.");
            }
            if(!is_int($value)){
                throw new DNSMnemonicException("All mapping values should be integers.");
            }
        }
        $this->mapping = $mapping;
        $this->allowInteger = $allowInteger;
        $this->fallbackDeserializer = $fallbackDeserializer;
        $this->fallbackSerializer = $fallbackSerializer;
    }

    /**
     * @param string $key
     * @return int
     * @throws DNSMnemonicException
     */
    public function deserializeMnemonic(string $key): int{
        if($this->allowInteger && preg_match('/^\d+$/',$key)){
            return intval($key);
        }
        if(isset($this->mapping[$key])){
            return $this->mapping[$key];
        }
        if($this->fallbackDeserializer){
            $value = ($this->fallbackDeserializer)($key);
            if(!is_null($value)){
                return $value;
            }
        }
        throw new DNSMnemonicException('Invalid mnemonic key during deserialization.');
    }

    /**
     * @param int $value
     * @return string
     * @throws DNSMnemonicException
     */
    public function serializeMnemonic(int $value): string{
        $reverseMapping = array_flip($this->mapping);
        if(isset($reverseMapping[$value])){
            return $reverseMapping[$value];
        }
        if($this->fallbackSerializer){
            $key = ($this->fallbackSerializer)($value);
            if(!is_null($key)){
                return $key;
            }
        }
        if($this->allowInteger){
            return strval($value);
        }
        throw new DNSMnemonicException('Invalid mnemonic value during serialization.');
    }

}