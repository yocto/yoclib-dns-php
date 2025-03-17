<?php
namespace YOCLIB\DNS;

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

        'TYPE1234' => 1234, //TODO Implement fallback
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

    private bool $allowInteger;

    public function __construct(array $mapping,?bool $allowInteger=true){
        $this->mapping = $mapping;
        $this->allowInteger = $allowInteger;
    }

    /**
     * @param string $value
     * @return int
     * @throws DNSMnemonicException
     */
    public function deserializeMnemonic(string $value): int{
        if($this->allowInteger && preg_match('/\d+/',$value)){
            return intval($value);
        }
        if(isset($this->mapping[$value])){
            return $this->mapping[$value];
        }
        throw new DNSMnemonicException('Unknown mnemonic.');
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
        if($this->allowInteger){
            return strval($value);
        }
        throw new DNSMnemonicException('Unknown mnemonic value.');
    }

}