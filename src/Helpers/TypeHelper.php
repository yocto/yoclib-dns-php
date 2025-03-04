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
use YOCLIB\DNS\Types\CDS;
use YOCLIB\DNS\Types\CLA;
use YOCLIB\DNS\Types\CNAME;
use YOCLIB\DNS\Types\DLV;
use YOCLIB\DNS\Types\DNAME;
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
use YOCLIB\DNS\Types\NINFO;
use YOCLIB\DNS\Types\NS;
use YOCLIB\DNS\Types\NSAP;
use YOCLIB\DNS\Types\NSAP_PTR;
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
        if($dataA===$dataB && $classA===$classB && $typeA===$typeB){
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
        if($type===DNSType::A){
            if(Unknown::detectUnknown($data)){
                return A::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return A::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::NS){
            if(Unknown::detectUnknown($data)){
                return NS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return NS::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::MD){
            if(Unknown::detectUnknown($data)){
                return MD::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return MD::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::MF){
            if(Unknown::detectUnknown($data)){
                return MF::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return MF::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::CNAME){
            if(Unknown::detectUnknown($data)){
                return CNAME::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return CNAME::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::SOA){
            if(Unknown::detectUnknown($data)){
                return SOA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return SOA::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::MB){
            if(Unknown::detectUnknown($data)){
                return MB::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return MB::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::MG){
            if(Unknown::detectUnknown($data)){
                return MG::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return MG::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::MR){
            if(Unknown::detectUnknown($data)){
                return MR::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return MR::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::NULL){
            return Unknown::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::WKS){
            if(Unknown::detectUnknown($data)){
                return WKS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return WKS::deserializeFromPresentationFormat($data,[/*TODO Improve mapping*/]);
        }
        if($type===DNSType::PTR){
            if(Unknown::detectUnknown($data)){
                return PTR::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return PTR::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::HINFO){
            if(Unknown::detectUnknown($data)){
                return HINFO::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return HINFO::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::MINFO){
            if(Unknown::detectUnknown($data)){
                return MINFO::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return MINFO::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::MX){
            if(Unknown::detectUnknown($data)){
                return MX::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return MX::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::TXT){
            if(Unknown::detectUnknown($data)){
                return TXT::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return TXT::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::RP){
            if(Unknown::detectUnknown($data)){
                return RP::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return RP::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::AFSDB){
            if(Unknown::detectUnknown($data)){
                return AFSDB::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return AFSDB::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::X25){
            if(Unknown::detectUnknown($data)){
                return X25::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return X25::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::ISDN){
            if(Unknown::detectUnknown($data)){
                return ISDN::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return ISDN::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::RT){
            if(Unknown::detectUnknown($data)){
                return RT::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return RT::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::NSAP){
            if(Unknown::detectUnknown($data)){
                return NSAP::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return NSAP::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::NSAP_PTR){
            if(Unknown::detectUnknown($data)){
                return NSAP_PTR::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return NSAP_PTR::deserializeFromPresentationFormat($data);
        }
        //TODO SIG
        if($type===DNSType::KEY){
            if(Unknown::detectUnknown($data)){
                return KEY::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return KEY::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::PX){
            if(Unknown::detectUnknown($data)){
                return PX::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return PX::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::GPOS){
            if(Unknown::detectUnknown($data)){
                return GPOS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return GPOS::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::AAAA){
            if(Unknown::detectUnknown($data)){
                return AAAA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return AAAA::deserializeFromPresentationFormat($data);
        }
        //TODO LOC
        //TODO NXT
        //TODO EID
        //TODO NIMLOC
        if($type===DNSType::SRV){
            if(Unknown::detectUnknown($data)){
                return SRV::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return SRV::deserializeFromPresentationFormat($data);
        }
        //TODO ATMA
        //TODO NAPTR
        if($type===DNSType::KX){
            if(Unknown::detectUnknown($data)){
                return KX::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return KX::deserializeFromPresentationFormat($data);
        }
        //TODO CERT
        //TODO A6
        if($type===DNSType::DNAME){
            if(Unknown::detectUnknown($data)){
                return DNAME::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return DNAME::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::SINK){
            if(Unknown::detectUnknown($data)){
                return SINK::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return SINK::deserializeFromPresentationFormat($data);
        }
        //TODO OPT
        //TODO APL
        if($type===DNSType::DS){
            if(Unknown::detectUnknown($data)){
                return DS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return DS::deserializeFromPresentationFormat($data);
        }
        //TODO SSHFP
        //TODO IPSECKEY
        //TODO RRSIG
        //TODO NSEC
        //TODO DNSKEY
        //TODO DHCID
        //TODO NSEC3
        //TODO NSEC3PARAM
        if($type===DNSType::TLSA){
            if(Unknown::detectUnknown($data)){
                return TLSA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return TLSA::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::SMIMEA){
            if(Unknown::detectUnknown($data)){
                return SMIMEA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return SMIMEA::deserializeFromPresentationFormat($data);
        }
        //TODO HIP
        if($type===DNSType::NINFO){
            if(Unknown::detectUnknown($data)){
                return NINFO::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return NINFO::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::RKEY){
            if(Unknown::detectUnknown($data)){
                return RKEY::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return RKEY::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::TALINK){
            if(Unknown::detectUnknown($data)){
                return TALINK::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return TALINK::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::CDS){
            if(Unknown::detectUnknown($data)){
                return CDS::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return CDS::deserializeFromPresentationFormat($data);
        }
        //TODO CDNSKEY
        //TODO OPENPGPKEY
        //TODO CSYNC
        //TODO ZONEMD
        //TODO SVCB
        //TODO HTTPS
//        if($type===DNSType::HTTPS){
//            try{
//                return HTTPS::deserializeFromPresentationFormat($data);
//            }catch(Throwable){}
//            return HTTPS::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
//        }
        //TODO DSYNC
        if($type===DNSType::SPF){
            if(Unknown::detectUnknown($data)){
                return SPF::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return SPF::deserializeFromPresentationFormat($data);
        }
        //TODO NID
        //TODO L32
        //TODO L64
        if($type===DNSType::LP){
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
        if($type===DNSType::URI){
            if(Unknown::detectUnknown($data)){
                return URI::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return URI::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::CAA){
            if(Unknown::detectUnknown($data)){
                return CAA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return CAA::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::AVC){
            if(Unknown::detectUnknown($data)){
                return AVC::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return AVC::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::DOA){
            if(Unknown::detectUnknown($data)){
                return DOA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return DOA::deserializeFromPresentationFormat($data);
        }
        //TODO AMTRELAY
        if($type===DNSType::RESINFO){
            if(Unknown::detectUnknown($data)){
                return RESINFO::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return RESINFO::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::WALLET){
            if(Unknown::detectUnknown($data)){
                return WALLET::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return WALLET::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::CLA){
            if(Unknown::detectUnknown($data)){
                return CLA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return CLA::deserializeFromPresentationFormat($data);
        }
        //TODO IPN
        if($type===DNSType::TA){
            if(Unknown::detectUnknown($data)){
                return TA::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return TA::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::DLV){
            if(Unknown::detectUnknown($data)){
                return DLV::deserializeFromWireFormat(TypeHelper::convertFromUnknown($data));
            }
            return DLV::deserializeFromPresentationFormat($data);
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
        if($type===DNSType::A){
            return A::deserializeFromWireFormat($data);
        }
        if($type===DNSType::NS){
            return NS::deserializeFromWireFormat($data);
        }
        if($type===DNSType::MD){
            return MD::deserializeFromWireFormat($data);
        }
        if($type===DNSType::MF){
            return MF::deserializeFromWireFormat($data);
        }
        if($type===DNSType::CNAME){
            return CNAME::deserializeFromWireFormat($data);
        }
        if($type===DNSType::SOA){
            return SOA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::MB){
            return MB::deserializeFromWireFormat($data);
        }
        if($type===DNSType::MG){
            return MG::deserializeFromWireFormat($data);
        }
        if($type===DNSType::MR){
            return MR::deserializeFromWireFormat($data);
        }
        if($type===DNSType::NULL){
            return Unknown::deserializeFromWireFormat($data);
        }
        if($type===DNSType::WKS){
            return WKS::deserializeFromWireFormat($data);
        }
        if($type===DNSType::PTR){
            return PTR::deserializeFromWireFormat($data);
        }
        if($type===DNSType::HINFO){
            return HINFO::deserializeFromWireFormat($data);
        }
        if($type===DNSType::MINFO){
            return MINFO::deserializeFromWireFormat($data);
        }
        if($type===DNSType::MX){
            return MX::deserializeFromWireFormat($data);
        }
        if($type===DNSType::TXT){
            return TXT::deserializeFromWireFormat($data);
        }
        if($type===DNSType::RP){
            return RP::deserializeFromWireFormat($data);
        }
        if($type===DNSType::AFSDB){
            return AFSDB::deserializeFromWireFormat($data);
        }
        if($type===DNSType::X25){
            return X25::deserializeFromWireFormat($data);
        }
        if($type===DNSType::ISDN){
            return ISDN::deserializeFromWireFormat($data);
        }
        if($type===DNSType::RT){
            return RT::deserializeFromWireFormat($data);
        }
        if($type===DNSType::NSAP){
            return NSAP::deserializeFromWireFormat($data);
        }
        if($type===DNSType::NSAP_PTR){
            return NSAP_PTR::deserializeFromWireFormat($data);
        }
        if($type===DNSType::KEY){
            return KEY::deserializeFromWireFormat($data);
        }
        if($type===DNSType::PX){
            return PX::deserializeFromWireFormat($data);
        }
        if($type===DNSType::GPOS){
            return GPOS::deserializeFromWireFormat($data);
        }
        if($type===DNSType::AAAA){
            return AAAA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::SRV){
            return SRV::deserializeFromWireFormat($data);
        }
        if($type===DNSType::DNAME){
            return DNAME::deserializeFromWireFormat($data);
        }
        if($type===DNSType::DS){
            return DS::deserializeFromWireFormat($data);
        }
        if($type===DNSType::TLSA){
            return TLSA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::SMIMEA){
            return SMIMEA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::NINFO){
            return NINFO::deserializeFromWireFormat($data);
        }
        if($type===DNSType::RKEY){
            return RKEY::deserializeFromWireFormat($data);
        }
        if($type===DNSType::TALINK){
            return TALINK::deserializeFromWireFormat($data);
        }
        if($type===DNSType::CDS){
            return CDS::deserializeFromWireFormat($data);
        }
        //TODO HTTPS
//        if($type===DNSType::HTTPS){
//            return HTTPS::deserializeFromWireFormat($data);
//        }
        if($type===DNSType::SPF){
            return SPF::deserializeFromWireFormat($data);
        }
        if($type===DNSType::LP){
            return LP::deserializeFromWireFormat($data);
        }
        if($type===DNSType::URI){
            return URI::deserializeFromWireFormat($data);
        }
        if($type===DNSType::CAA){
            return CAA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::AVC){
            return AVC::deserializeFromWireFormat($data);
        }
        if($type===DNSType::DOA){
            return DOA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::RESINFO){
            return RESINFO::deserializeFromWireFormat($data);
        }
        if($type===DNSType::WALLET){
            return WALLET::deserializeFromWireFormat($data);
        }
        if($type===DNSType::CLA){
            return CLA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::TA){
            return TA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::DLV){
            return DLV::deserializeFromWireFormat($data);
        }
        throw new DNSTypeException('Trying to deserialize an unsupported type from wire format.');
    }

}