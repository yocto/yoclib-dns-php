<?php
namespace YOCLIB\DNS\Helpers;

use Throwable;

use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Types\A;
use YOCLIB\DNS\Types\AAAA;
use YOCLIB\DNS\Types\AFSDB;
use YOCLIB\DNS\Types\CAA;
use YOCLIB\DNS\Types\CNAME;
use YOCLIB\DNS\Types\DS;
use YOCLIB\DNS\Types\HINFO;
use YOCLIB\DNS\Types\ISDN;
use YOCLIB\DNS\Types\MB;
use YOCLIB\DNS\Types\MD;
use YOCLIB\DNS\Types\MF;
use YOCLIB\DNS\Types\MG;
use YOCLIB\DNS\Types\MINFO;
use YOCLIB\DNS\Types\MR;
use YOCLIB\DNS\Types\MX;
use YOCLIB\DNS\Types\NS;
use YOCLIB\DNS\Types\NSAP;
use YOCLIB\DNS\Types\NSAP_PTR;
use YOCLIB\DNS\Types\PTR;
use YOCLIB\DNS\Types\RP;
use YOCLIB\DNS\Types\RT;
use YOCLIB\DNS\Types\SOA;
use YOCLIB\DNS\Types\SPF;
use YOCLIB\DNS\Types\SRV;
use YOCLIB\DNS\Types\TXT;
use YOCLIB\DNS\Types\Type;
use YOCLIB\DNS\Types\Unknown;
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
            try{
                return A::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return A::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::NS){
            try{
                return NS::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return NS::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::MD){
            try{
                return MD::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return MD::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::MF){
            try{
                return MF::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return MF::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::CNAME){
            try{
                return CNAME::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return CNAME::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::SOA){
            try{
                return SOA::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return SOA::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::MB){
            try{
                return MB::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return MB::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::MG){
            try{
                return MG::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return MG::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::MR){
            try{
                return MR::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return MR::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::NULL){
            return Unknown::deserializeFromPresentationFormat($data);
        }
        if($type===DNSType::WKS){
            try{
                return WKS::deserializeFromPresentationFormat($data,[/*TODO Improve mapping*/]);
            }catch(Throwable){}
            return WKS::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::PTR){
            try{
                return PTR::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return PTR::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::HINFO){
            try{
                return HINFO::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return HINFO::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::MINFO){
            try{
                return MINFO::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return MINFO::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::MX){
            try{
                return MX::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return MX::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::TXT){
            try{
                return TXT::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return TXT::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::RP){
            try{
                return RP::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return RP::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::AFSDB){
            try{
                return AFSDB::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return AFSDB::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::X25){
            try{
                return X25::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return X25::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::ISDN){
            try{
                return ISDN::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return ISDN::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::RT){
            try{
                return RT::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return RT::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }

        if($type===DNSType::NSAP){
            try{
                return NSAP::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return NSAP::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::NSAP_PTR){
            try{
                return NSAP_PTR::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return NSAP_PTR::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        //TODO SIG
        //TODO KEY
        //TODO PX
        //TODO GPOS
        if($type===DNSType::AAAA){
            try{
                return AAAA::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return AAAA::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        //TODO LOC
        //TODO NXT
        //TODO EID
        //TODO NIMLOC
        if($type===DNSType::SRV){
            try{
                return SRV::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return SRV::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        //TODO ATMA
        //TODO NAPTR
        //TODO KX
        //TODO CERT
        //TODO A6
        //TODO DNAME
        if($type===DNSType::DS){
            try{
                return DS::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return DS::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::SPF){
            try{
                return SPF::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return SPF::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
        }
        if($type===DNSType::CAA){
            try{
                return CAA::deserializeFromPresentationFormat($data);
            }catch(Throwable){}
            return CAA::deserializeFromWireFormat(Unknown::deserializeFromPresentationFormat($data)->serializeToWireFormat());
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
        if($type===DNSType::AAAA){
            return AAAA::deserializeFromWireFormat($data);
        }
        if($type===DNSType::SRV){
            return SRV::deserializeFromWireFormat($data);
        }
        if($type===DNSType::DS){
            return DS::deserializeFromWireFormat($data);
        }
        if($type===DNSType::SPF){
            return SPF::deserializeFromWireFormat($data);
        }
        if($type===DNSType::CAA){
            return CAA::deserializeFromWireFormat($data);
        }
        throw new DNSTypeException('Trying to deserialize an unsupported type from wire format.');
    }

}