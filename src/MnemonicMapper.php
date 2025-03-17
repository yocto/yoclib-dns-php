<?php
namespace YOCLIB\DNS;

use Closure;

use YOCLIB\DNS\Exceptions\DNSMnemonicException;

class MnemonicMapper{

    public const MAPPING_DNS_TYPES = [
        'A' => DNSType::A,
        'NS' => DNSType::NS,
        'MD' => DNSType::MD,
        'MF' => DNSType::MF,
        'CNAME' => DNSType::CNAME,
        'SOA' => DNSType::SOA,
        'MB' => DNSType::MB,
        'MG' => DNSType::MG,
        'MR' => DNSType::MR,
        'NULL' => DNSType::NULL,
        'WKS' => DNSType::WKS,
        'PTR' => DNSType::PTR,
        'HINFO' => DNSType::HINFO,
        'MINFO' => DNSType::MINFO,
        'MX' => DNSType::MX,
        'TXT' => DNSType::TXT,
        'RP' => DNSType::RP,
        'AFSDB' => DNSType::AFSDB,
        'X25' => DNSType::X25,
        'ISDN' => DNSType::ISDN,
        'RT' => DNSType::RT,
        'NSAP' => DNSType::NSAP,
        'NSAP-PTR' => DNSType::NSAP_PTR,
        'SIG' => DNSType::SIG,
        'KEY' => DNSType::KEY,
        'PX' => DNSType::PX,
        'GPOS' => DNSType::GPOS,
        'AAAA' => DNSType::AAAA,
        'LOC' => DNSType::LOC,
        'NXT' => DNSType::NXT,
        'EID' => DNSType::EID,
        'NIMLOC' => DNSType::NIMLOC,
        'SRV' => DNSType::SRV,
        'ATMA' => DNSType::ATMA,
        'NAPTR' => DNSType::NAPTR,
        'KX' => DNSType::KX,
        'CERT' => DNSType::CERT,
        'A6' => DNSType::A6,
        'DNAME' => DNSType::DNAME,
        'SINK' => DNSType::SINK,
        'OPT' => DNSType::OPT,
        'APL' => DNSType::APL,
        'DS' => DNSType::DS,
        'SSHFP' => DNSType::SSHFP,
        'IPSECKEY' => DNSType::IPSECKEY,
        'RRSIG' => DNSType::RRSIG,
        'NSEC' => DNSType::NSEC,
        'DNSKEY' => DNSType::DNSKEY,
        'DHCID' => DNSType::DHCID,
        'NSEC3' => DNSType::NSEC3,
        'NSEC3PARAM' => DNSType::NSEC3PARAM,
        'TLSA' => DNSType::TLSA,
        'SMIMEA' => DNSType::SMIMEA,

        'HIP' => DNSType::HIP,
        'NINFO' => DNSType::NINFO,
        'RKEY' => DNSType::RKEY,
        'TALINK' => DNSType::TALINK,
        'CDS' => DNSType::CDS,
        'CDNSKEY' => DNSType::CDNSKEY,
        'OPENPGPKEY' => DNSType::OPENPGPKEY,
        'CSYNC' => DNSType::CSYNC,
        'ZONEMD' => DNSType::ZONEMD,
        'SVCB' => DNSType::SVCB,
        'HTTPS' => DNSType::HTTPS,
        'DSYNC' => DNSType::DSYNC,

        'SPF' => DNSType::SPF,
        'UNINFO' => DNSType::UNINFO,
        'UID' => DNSType::UID,
        'GID' => DNSType::GID,
        'UNSPEC' => DNSType::UNSPEC,
        'NID' => DNSType::NID,
        'L32' => DNSType::L32,
        'L64' => DNSType::L64,
        'LP' => DNSType::LP,
        'EUI48' => DNSType::EUI48,
        'EUI64' => DNSType::EUI64,

        'NXNAME' => DNSType::NXNAME,

        'TKEY' => DNSType::TKEY,
        'TSIG' => DNSType::TSIG,
        'IXFR' => DNSType::IXFR,
        'AXFR' => DNSType::AXFR,
        'MAILB' => DNSType::MAILB,
        'MAILA' => DNSType::MAILA,
        'ANY' => DNSType::ANY,
        'URI' => DNSType::URI,
        'CAA' => DNSType::CAA,
        'AVC' => DNSType::AVC,
        'DOA' => DNSType::DOA,
        'AMTRELAY' => DNSType::AMTRELAY,
        'RESINFO' => DNSType::RESINFO,
        'WALLET' => DNSType::WALLET,
        'CLA' => DNSType::CLA,
        'IPN' => DNSType::IPN,

        'TA' => DNSType::TA,
        'DLV' => DNSType::DLV,
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