<?php
namespace YOCLIB\DNS\Helpers;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Types\A;
use YOCLIB\DNS\Types\AAAA;
use YOCLIB\DNS\Types\AFSDB;
use YOCLIB\DNS\Types\AVC;
use YOCLIB\DNS\Types\CAA;
use YOCLIB\DNS\Types\CDNSKEY;
use YOCLIB\DNS\Types\CDS;
use YOCLIB\DNS\Types\CLA;
use YOCLIB\DNS\Types\CNAME;
use YOCLIB\DNS\Types\CSYNC;
use YOCLIB\DNS\Types\DHCID;
use YOCLIB\DNS\Types\DLV;
use YOCLIB\DNS\Types\DNAME;
use YOCLIB\DNS\Types\DNSKEY;
use YOCLIB\DNS\Types\DOA;
use YOCLIB\DNS\Types\DS;
use YOCLIB\DNS\Types\GPOS;
use YOCLIB\DNS\Types\HINFO;
use YOCLIB\DNS\Types\ISDN;
use YOCLIB\DNS\Types\KEY;
use YOCLIB\DNS\Types\KX;
use YOCLIB\DNS\Types\LP;
use YOCLIB\DNS\Types\MB;
use YOCLIB\DNS\Types\MD;
use YOCLIB\DNS\Types\MF;
use YOCLIB\DNS\Types\MG;
use YOCLIB\DNS\Types\MINFO;
use YOCLIB\DNS\Types\MR;
use YOCLIB\DNS\Types\MX;
use YOCLIB\DNS\Types\NAPTR;
use YOCLIB\DNS\Types\NINFO;
use YOCLIB\DNS\Types\NS;
use YOCLIB\DNS\Types\NSAP;
use YOCLIB\DNS\Types\NSAP_PTR;
use YOCLIB\DNS\Types\NSEC3PARAM;
use YOCLIB\DNS\Types\NULLType;
use YOCLIB\DNS\Types\OPENPGPKEY;
use YOCLIB\DNS\Types\PTR;
use YOCLIB\DNS\Types\PX;
use YOCLIB\DNS\Types\RESINFO;
use YOCLIB\DNS\Types\RKEY;
use YOCLIB\DNS\Types\RP;
use YOCLIB\DNS\Types\RT;
use YOCLIB\DNS\Types\SINK;
use YOCLIB\DNS\Types\SMIMEA;
use YOCLIB\DNS\Types\SOA;
use YOCLIB\DNS\Types\SPF;
use YOCLIB\DNS\Types\SRV;
use YOCLIB\DNS\Types\TA;
use YOCLIB\DNS\Types\TALINK;
use YOCLIB\DNS\Types\TLSA;
use YOCLIB\DNS\Types\TXT;
use YOCLIB\DNS\Types\Type;
use YOCLIB\DNS\Types\Unknown;
use YOCLIB\DNS\Types\URI;
use YOCLIB\DNS\Types\WALLET;
use YOCLIB\DNS\Types\WKS;
use YOCLIB\DNS\Types\X25;
use YOCLIB\DNS\Types\ZONEMD;

class TypeHelper{

    /**
     * @param string $nameA
     * @param string $dataA
     * @param int $classA
     * @param int $typeA
     * @param string $nameB
     * @param string $dataB
     * @param int $classB
     * @param int $typeB
     * @return int
     * @throws DNSFieldException
     */
    public static function compare(string $nameA,string $dataA,int $classA,int $typeA,string $nameB,string $dataB,int $classB,int $typeB): int{
        if($nameA===$nameB && $dataA===$dataB && $classA===$classB && $typeA===$typeB){
            return 0;
        }

        $diff = FQDN::compare(FQDN::deserializeFromPresentationFormat($nameA),FQDN::deserializeFromPresentationFormat($nameB));
        if($diff!=0){
            return $diff;
        }

        $diff = $classA - $classB;
        if($diff!=0){
            return $diff;
        }

        $diff = $typeA - $typeB;
        if($diff!=0){
            return $diff;
        }

        for($i=0;($i<strlen($dataA) && $i<strlen($dataB));$i++){
            $diff = ord($dataA[$i]) - ord($dataB[$i]);
            if($diff!=0){
                return $diff;
            }
        }

        return strlen($dataA) - strlen($dataB);
    }

    /**
     * @param string $data
     * @return string
     * @throws DNSTypeException
     */
    public static function convertFromUnknown(string $data): string{
        return Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat();
    }

    /**
     * @param string $data
     * @param int $class
     * @param int $type
     * @return Type
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromPresentationFormatByClassAndType(string $data,int $class,int $type): Type{
        if($class===0){
            throw new DNSTypeException('Class cannot be zero.');
        }
        if($type===0){
            throw new DNSTypeException('Type cannot be zero.');
        }
        switch($type){
            case DNSType::A:{
                if(Unknown::detectUnknown($data)){
                    return A::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return A::deserializeFromPresentationFormat($data);
            }
            case DNSType::NS:{
                if(Unknown::detectUnknown($data)){
                    return NS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return NS::deserializeFromPresentationFormat($data);
            }
            case DNSType::MD:{
                if(Unknown::detectUnknown($data)){
                    return MD::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return MD::deserializeFromPresentationFormat($data);
            }
            case DNSType::MF:{
                if(Unknown::detectUnknown($data)){
                    return MF::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return MF::deserializeFromPresentationFormat($data);
            }
            case DNSType::CNAME:{
                if(Unknown::detectUnknown($data)){
                    return CNAME::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return CNAME::deserializeFromPresentationFormat($data);
            }
            case DNSType::SOA:{
                if(Unknown::detectUnknown($data)){
                    return SOA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return SOA::deserializeFromPresentationFormat($data);
            }
            case DNSType::MB:{
                if(Unknown::detectUnknown($data)){
                    return MB::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return MB::deserializeFromPresentationFormat($data);
            }
            case DNSType::MG:{
                if(Unknown::detectUnknown($data)){
                    return MG::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return MG::deserializeFromPresentationFormat($data);
            }
            case DNSType::MR:{
                if(Unknown::detectUnknown($data)){
                    return MR::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return MR::deserializeFromPresentationFormat($data);
            }
            case DNSType::NULL:{
                return NULLType::deserializeFromPresentationFormat($data);
            }
            case DNSType::WKS:{
                if(Unknown::detectUnknown($data)){
                    return WKS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return WKS::deserializeFromPresentationFormat($data,[/*TODO Improve mapping*/]);
            }
            case DNSType::PTR:{
                if(Unknown::detectUnknown($data)){
                    return PTR::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return PTR::deserializeFromPresentationFormat($data);
            }
            case DNSType::HINFO:{
                if(Unknown::detectUnknown($data)){
                    return HINFO::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return HINFO::deserializeFromPresentationFormat($data);
            }
            case DNSType::MINFO:{
                if(Unknown::detectUnknown($data)){
                    return MINFO::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return MINFO::deserializeFromPresentationFormat($data);
            }
            case DNSType::MX:{
                if(Unknown::detectUnknown($data)){
                    return MX::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return MX::deserializeFromPresentationFormat($data);
            }
            case DNSType::TXT:{
                if(Unknown::detectUnknown($data)){
                    return TXT::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return TXT::deserializeFromPresentationFormat($data);
            }
            case DNSType::RP:{
                if(Unknown::detectUnknown($data)){
                    return RP::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return RP::deserializeFromPresentationFormat($data);
            }
            case DNSType::AFSDB:{
                if(Unknown::detectUnknown($data)){
                    return AFSDB::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return AFSDB::deserializeFromPresentationFormat($data);
            }
            case DNSType::X25:{
                if(Unknown::detectUnknown($data)){
                    return X25::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return X25::deserializeFromPresentationFormat($data);
            }
            case DNSType::ISDN:{
                if(Unknown::detectUnknown($data)){
                    return ISDN::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return ISDN::deserializeFromPresentationFormat($data);
            }
            case DNSType::RT:{
                if(Unknown::detectUnknown($data)){
                    return RT::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return RT::deserializeFromPresentationFormat($data);
            }
            case DNSType::NSAP:{
                if(Unknown::detectUnknown($data)){
                    return NSAP::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return NSAP::deserializeFromPresentationFormat($data);
            }
            case DNSType::NSAP_PTR:{
                if(Unknown::detectUnknown($data)){
                    return NSAP_PTR::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return NSAP_PTR::deserializeFromPresentationFormat($data);
            }
            //TODO SIG
            case DNSType::KEY:{
                if(Unknown::detectUnknown($data)){
                    return KEY::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return KEY::deserializeFromPresentationFormat($data);
            }
            case DNSType::PX:{
                if(Unknown::detectUnknown($data)){
                    return PX::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return PX::deserializeFromPresentationFormat($data);
            }
            case DNSType::GPOS:{
                if(Unknown::detectUnknown($data)){
                    return GPOS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return GPOS::deserializeFromPresentationFormat($data);
            }
            case DNSType::AAAA:{
                if(Unknown::detectUnknown($data)){
                    return AAAA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return AAAA::deserializeFromPresentationFormat($data);
            }
            //TODO LOC
            //TODO NXT
            //TODO EID
            //TODO NIMLOC
            case DNSType::SRV:{
                if(Unknown::detectUnknown($data)){
                    return SRV::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return SRV::deserializeFromPresentationFormat($data);
            }
            //TODO ATMA
            case DNSType::NAPTR:{
                if(Unknown::detectUnknown($data)){
                    return NAPTR::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return NAPTR::deserializeFromPresentationFormat($data);
            }
            case DNSType::KX:{
                if(Unknown::detectUnknown($data)){
                    return KX::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return KX::deserializeFromPresentationFormat($data);
            }
            //TODO CERT
            //TODO A6
            case DNSType::DNAME:{
                if(Unknown::detectUnknown($data)){
                    return DNAME::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return DNAME::deserializeFromPresentationFormat($data);
            }
            case DNSType::SINK:{
                if(Unknown::detectUnknown($data)){
                    return SINK::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return SINK::deserializeFromPresentationFormat($data);
            }
            //TODO OPT
            //TODO APL
            case DNSType::DS:{
                if(Unknown::detectUnknown($data)){
                    return DS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return DS::deserializeFromPresentationFormat($data);
            }
            //TODO SSHFP
            //TODO IPSECKEY
            //TODO RRSIG
            //TODO NSEC
            case DNSType::DNSKEY:{
                if(Unknown::detectUnknown($data)){
                    return DNSKEY::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return DNSKEY::deserializeFromPresentationFormat($data);
            }
            case DNSType::DHCID:{
                if(Unknown::detectUnknown($data)){
                    return DHCID::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return DHCID::deserializeFromPresentationFormat($data);
            }
            //TODO NSEC3
            case DNSType::NSEC3PARAM:{
                if(Unknown::detectUnknown($data)){
                    return NSEC3PARAM::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return NSEC3PARAM::deserializeFromPresentationFormat($data);
            }
            case DNSType::TLSA:{
                if(Unknown::detectUnknown($data)){
                    return TLSA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return TLSA::deserializeFromPresentationFormat($data);
            }
            case DNSType::SMIMEA:{
                if(Unknown::detectUnknown($data)){
                    return SMIMEA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return SMIMEA::deserializeFromPresentationFormat($data);
            }
            //TODO HIP
            case DNSType::NINFO:{
                if(Unknown::detectUnknown($data)){
                    return NINFO::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return NINFO::deserializeFromPresentationFormat($data);
            }
            case DNSType::RKEY:{
                if(Unknown::detectUnknown($data)){
                    return RKEY::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return RKEY::deserializeFromPresentationFormat($data);
            }
            case DNSType::TALINK:{
                if(Unknown::detectUnknown($data)){
                    return TALINK::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return TALINK::deserializeFromPresentationFormat($data);
            }
            case DNSType::CDS:{
                if(Unknown::detectUnknown($data)){
                    return CDS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return CDS::deserializeFromPresentationFormat($data);
            }
            case DNSType::CDNSKEY:{
                if(Unknown::detectUnknown($data)){
                    return CDNSKEY::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return CDNSKEY::deserializeFromPresentationFormat($data);
            }
            case DNSType::OPENPGPKEY:{
                if(Unknown::detectUnknown($data)){
                    return OPENPGPKEY::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return OPENPGPKEY::deserializeFromPresentationFormat($data);
            }
            case DNSType::CSYNC:{
                if(Unknown::detectUnknown($data)){
                    return CSYNC::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return CSYNC::deserializeFromPresentationFormat($data);
            }
            case DNSType::ZONEMD:{
                if(Unknown::detectUnknown($data)){
                    return ZONEMD::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return ZONEMD::deserializeFromPresentationFormat($data);
            }
            //TODO SVCB
            //TODO HTTPS
            //TODO DSYNC
            case DNSType::SPF:{
                if(Unknown::detectUnknown($data)){
                    return SPF::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return SPF::deserializeFromPresentationFormat($data);
            }
            //TODO NID
            //TODO L32
            //TODO L64
            case DNSType::LP:{
                if(Unknown::detectUnknown($data)){
                    return LP::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return LP::deserializeFromPresentationFormat($data);
            }
            //TODO EUI48
            //TODO EUI64
            //TODO NXNAME
            //TODO TKEY
            //TODO TSIG
            case DNSType::URI:{
                if(Unknown::detectUnknown($data)){
                    return URI::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return URI::deserializeFromPresentationFormat($data);
            }
            case DNSType::CAA:{
                if(Unknown::detectUnknown($data)){
                    return CAA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return CAA::deserializeFromPresentationFormat($data);
            }
            case DNSType::AVC:{
                if(Unknown::detectUnknown($data)){
                    return AVC::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return AVC::deserializeFromPresentationFormat($data);
            }
            case DNSType::DOA:{
                if(Unknown::detectUnknown($data)){
                    return DOA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return DOA::deserializeFromPresentationFormat($data);
            }
            //TODO AMTRELAY
            case DNSType::RESINFO:{
                if(Unknown::detectUnknown($data)){
                    return RESINFO::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return RESINFO::deserializeFromPresentationFormat($data);
            }
            case DNSType::WALLET:{
                if(Unknown::detectUnknown($data)){
                    return WALLET::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return WALLET::deserializeFromPresentationFormat($data);
            }
            case DNSType::CLA:{
                if(Unknown::detectUnknown($data)){
                    return CLA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return CLA::deserializeFromPresentationFormat($data);
            }
            //TODO IPN
            case DNSType::TA:{
                if(Unknown::detectUnknown($data)){
                    return TA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return TA::deserializeFromPresentationFormat($data);
            }
            case DNSType::DLV:{
                if(Unknown::detectUnknown($data)){
                    return DLV::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
                }
                return DLV::deserializeFromPresentationFormat($data);
            }
        }
        throw new DNSTypeException('Trying to deserialize an unsupported type from presentation format.');
    }

    /**
     * @param string $data
     * @param int $class
     * @param int $type
     * @return Type
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public static function deserializeFromWireFormatByClassAndType(string $data,int $class,int $type): Type{
        if($class===0){
            throw new DNSTypeException('Class cannot be zero.');
        }
        if($type===0){
            throw new DNSTypeException('Type cannot be zero.');
        }
        switch($type){
            case DNSType::A:{
                return A::deserializeFromWireFormat($data);
            }
            case DNSType::NS:{
                return NS::deserializeFromWireFormat($data);
            }
            case DNSType::MD:{
                return MD::deserializeFromWireFormat($data);
            }
            case DNSType::MF:{
                return MF::deserializeFromWireFormat($data);
            }
            case DNSType::CNAME:{
                return CNAME::deserializeFromWireFormat($data);
            }
            case DNSType::SOA:{
                return SOA::deserializeFromWireFormat($data);
            }
            case DNSType::MB:{
                return MB::deserializeFromWireFormat($data);
            }
            case DNSType::MG:{
                return MG::deserializeFromWireFormat($data);
            }
            case DNSType::MR:{
                return MR::deserializeFromWireFormat($data);
            }
            case DNSType::NULL:{
                return NULLType::deserializeFromWireFormat($data);
            }
            case DNSType::WKS:{
                return WKS::deserializeFromWireFormat($data);
            }
            case DNSType::PTR:{
                return PTR::deserializeFromWireFormat($data);
            }
            case DNSType::HINFO:{
                return HINFO::deserializeFromWireFormat($data);
            }
            case DNSType::MINFO:{
                return MINFO::deserializeFromWireFormat($data);
            }
            case DNSType::MX:{
                return MX::deserializeFromWireFormat($data);
            }
            case DNSType::TXT:{
                return TXT::deserializeFromWireFormat($data);
            }
            case DNSType::RP:{
                return RP::deserializeFromWireFormat($data);
            }
            case DNSType::AFSDB:{
                return AFSDB::deserializeFromWireFormat($data);
            }
            case DNSType::X25:{
                return X25::deserializeFromWireFormat($data);
            }
            case DNSType::ISDN:{
                return ISDN::deserializeFromWireFormat($data);
            }
            case DNSType::RT:{
                return RT::deserializeFromWireFormat($data);
            }
            case DNSType::NSAP:{
                return NSAP::deserializeFromWireFormat($data);
            }
            case DNSType::NSAP_PTR:{
                return NSAP_PTR::deserializeFromWireFormat($data);
            }
            case DNSType::KEY:{
                return KEY::deserializeFromWireFormat($data);
            }
            case DNSType::PX:{
                return PX::deserializeFromWireFormat($data);
            }
            case DNSType::GPOS:{
                return GPOS::deserializeFromWireFormat($data);
            }
            case DNSType::AAAA:{
                return AAAA::deserializeFromWireFormat($data);
            }
            case DNSType::SRV:{
                return SRV::deserializeFromWireFormat($data);
            }
            case DNSType::NAPTR:{
                return NAPTR::deserializeFromWireFormat($data);
            }
            case DNSType::KX:{
                return KX::deserializeFromWireFormat($data);
            }
            case DNSType::DNAME:{
                return DNAME::deserializeFromWireFormat($data);
            }
            case DNSType::SINK:{
                return SINK::deserializeFromWireFormat($data);
            }
            case DNSType::DS:{
                return DS::deserializeFromWireFormat($data);
            }
            case DNSType::DNSKEY:{
                return DNSKEY::deserializeFromWireFormat($data);
            }
            case DNSType::DHCID:{
                return DHCID::deserializeFromWireFormat($data);
            }
            case DNSType::NSEC3PARAM:{
                return NSEC3PARAM::deserializeFromWireFormat($data);
            }
            case DNSType::TLSA:{
                return TLSA::deserializeFromWireFormat($data);
            }
            case DNSType::SMIMEA:{
                return SMIMEA::deserializeFromWireFormat($data);
            }
            case DNSType::NINFO:{
                return NINFO::deserializeFromWireFormat($data);
            }
            case DNSType::RKEY:{
                return RKEY::deserializeFromWireFormat($data);
            }
            case DNSType::TALINK:{
                return TALINK::deserializeFromWireFormat($data);
            }
            case DNSType::CDS:{
                return CDS::deserializeFromWireFormat($data);
            }
            case DNSType::CDNSKEY:{
                return CDNSKEY::deserializeFromWireFormat($data);
            }
            case DNSType::OPENPGPKEY:{
                return OPENPGPKEY::deserializeFromWireFormat($data);
            }
            case DNSType::CSYNC:{
                return CSYNC::deserializeFromWireFormat($data);
            }
            case DNSType::ZONEMD:{
                return ZONEMD::deserializeFromWireFormat($data);
            }
            //TODO HTTPS
            case DNSType::SPF:{
                return SPF::deserializeFromWireFormat($data);
            }
            case DNSType::LP:{
                return LP::deserializeFromWireFormat($data);
            }
            case DNSType::URI:{
                return URI::deserializeFromWireFormat($data);
            }
            case DNSType::CAA:{
                return CAA::deserializeFromWireFormat($data);
            }
            case DNSType::AVC:{
                return AVC::deserializeFromWireFormat($data);
            }
            case DNSType::DOA:{
                return DOA::deserializeFromWireFormat($data);
            }
            case DNSType::RESINFO:{
                return RESINFO::deserializeFromWireFormat($data);
            }
            case DNSType::WALLET:{
                return WALLET::deserializeFromWireFormat($data);
            }
            case DNSType::CLA:{
                return CLA::deserializeFromWireFormat($data);
            }
            case DNSType::TA:{
                return TA::deserializeFromWireFormat($data);
            }
            case DNSType::DLV:{
                return DLV::deserializeFromWireFormat($data);
            }
        }
        throw new DNSTypeException('Trying to deserialize an unsupported type from wire format.');
    }

}