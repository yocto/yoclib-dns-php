<?php
namespace YOCLIB\DNS\Tests\Helpers;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSClass;
use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Helpers\TypeHelper;
use YOCLIB\DNS\Types\A;
use YOCLIB\DNS\Types\AAAA;
use YOCLIB\DNS\Types\AFSDB;
use YOCLIB\DNS\Types\CNAME;
use YOCLIB\DNS\Types\DNAME;
use YOCLIB\DNS\Types\DS;
use YOCLIB\DNS\Types\GPOS;
use YOCLIB\DNS\Types\HINFO;
use YOCLIB\DNS\Types\ISDN;
use YOCLIB\DNS\Types\KX;
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
use YOCLIB\DNS\Types\NULLType;
use YOCLIB\DNS\Types\PTR;
use YOCLIB\DNS\Types\PX;
use YOCLIB\DNS\Types\RP;
use YOCLIB\DNS\Types\RT;
use YOCLIB\DNS\Types\SOA;
use YOCLIB\DNS\Types\SRV;
use YOCLIB\DNS\Types\TXT;
use YOCLIB\DNS\Types\WKS;
use YOCLIB\DNS\Types\X25;

class TypeHelperTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatByClassAndType(): void{
        $this->assertInstanceOf(A::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('127.0.0.1',DNSClass::IN,DNSType::A));
        $this->assertInstanceOf(A::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 4 7F000001',DNSClass::IN,DNSType::A));

        $this->assertInstanceOf(NS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::NS));
        $this->assertInstanceOf(NS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::NS));

        $this->assertInstanceOf(MD::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::MD));
        $this->assertInstanceOf(MD::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::MD));

        $this->assertInstanceOf(MF::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::MF));
        $this->assertInstanceOf(MF::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::MF));

        $this->assertInstanceOf(CNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::CNAME));
        $this->assertInstanceOf(CNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::CNAME));

        $this->assertInstanceOf(SOA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('mailbox.example.com. example.com. 1 2 3 4 5',DNSClass::IN,DNSType::SOA));
        $this->assertInstanceOf(SOA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 54 076D61696C626F78076578616D706C6503636F6D00 076578616D706C6503636F6D00 00000001 00000002 00000003 00000004 00000005',DNSClass::IN,DNSType::SOA));

        $this->assertInstanceOf(MB::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::MB));
        $this->assertInstanceOf(MB::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::MB));

        $this->assertInstanceOf(MG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::MG));
        $this->assertInstanceOf(MG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::MG));

        $this->assertInstanceOf(MR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::MR));
        $this->assertInstanceOf(MR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::MR));

        $this->assertInstanceOf(NULLType::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 16 000102030405060708090A0B0C0D0E0F',DNSClass::IN,DNSType::NULL));

        //$this->assertInstanceOf(WKS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('127.0.0.1 6 SMTP',DNSClass::IN,DNSType::WKS));
        $this->assertInstanceOf(WKS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 8 7F000001 06 000002',DNSClass::IN,DNSType::WKS));

        $this->assertInstanceOf(PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::PTR));
        $this->assertInstanceOf(PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::PTR));

        $this->assertInstanceOf(HINFO::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('"Some CPU" "Some OS"',DNSClass::IN,DNSType::HINFO));
        $this->assertInstanceOf(HINFO::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 17 08536F6D6520435055 07536F6D65204F53',DNSClass::IN,DNSType::HINFO));

        $this->assertInstanceOf(MINFO::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('rmailbx.example.com. emailbx.example.com.',DNSClass::IN,DNSType::MINFO));
        $this->assertInstanceOf(MINFO::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 42 07726D61696C6278076578616D706C6503636F6D00 07656D61696C6278076578616D706C6503636F6D00',DNSClass::IN,DNSType::MINFO));

        $this->assertInstanceOf(MX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 mx.example.com.',DNSClass::IN,DNSType::MX));
        $this->assertInstanceOf(MX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 18 000A 026D78076578616D706C6503636F6D00',DNSClass::IN,DNSType::MX));

        $this->assertInstanceOf(TXT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('"Text 1" "Text 2" "Text 3"',DNSClass::IN,DNSType::TXT));
        $this->assertInstanceOf(TXT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 21 06546578742031 06546578742032 06546578742033',DNSClass::IN,DNSType::TXT));

        $this->assertInstanceOf(RP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('mailbox.example.com. text.example.com.',DNSClass::IN,DNSType::RP));
        $this->assertInstanceOf(RP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 39 076D61696C626F78076578616D706C6503636F6D00 0474657874076578616D706C6503636F6D00',DNSClass::IN,DNSType::RP));

        $this->assertInstanceOf(AFSDB::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 afs.example.com.',DNSClass::IN,DNSType::AFSDB));
        $this->assertInstanceOf(AFSDB::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 19 0001 03616673076578616D706C6503636F6D00',DNSClass::IN,DNSType::AFSDB));

        $this->assertInstanceOf(X25::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('311061700956',DNSClass::IN,DNSType::X25));
        $this->assertInstanceOf(X25::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 0C333131303631373030393536',DNSClass::IN,DNSType::X25));

        $this->assertInstanceOf(ISDN::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('150862028003217',DNSClass::IN,DNSType::ISDN));
        $this->assertInstanceOf(ISDN::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('150862028003217 004',DNSClass::IN,DNSType::ISDN));
        $this->assertInstanceOf(ISDN::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 9 081508620280032170',DNSClass::IN,DNSType::ISDN));
        $this->assertInstanceOf(ISDN::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 081508620280032170 03303034',DNSClass::IN,DNSType::ISDN));

        $this->assertInstanceOf(RT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('20 relay.example.com.',DNSClass::IN,DNSType::RT));
        $this->assertInstanceOf(RT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 21 0014 0572656C6179076578616D706C6503636F6D00',DNSClass::IN,DNSType::RT));

        $this->assertInstanceOf(NSAP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('0x47.0005.80.005a00.0000.0001.e133.ffffff000161.00',DNSClass::IN,DNSType::NSAP));
        $this->assertInstanceOf(NSAP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 20 47000580005A0000000001E133FFFFFF00016100',DNSClass::IN,DNSType::NSAP));

        $this->assertInstanceOf(NSAP_PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::NSAP_PTR));
        $this->assertInstanceOf(NSAP_PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::NSAP_PTR));

        //TODO SIG

        //TODO KEY

        $this->assertInstanceOf(PX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 map822.example.com. mapx400.example.com.',DNSClass::IN,DNSType::PX));
        $this->assertInstanceOf(PX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 43 000A 066D6170383232076578616D706C6503636F6D00 076D617078343030076578616D706C6503636F6D00',DNSClass::IN,DNSType::PX));

        $this->assertInstanceOf(GPOS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('-32.6882 116.8652 10.0',DNSClass::IN,DNSType::GPOS));
        $this->assertInstanceOf(GPOS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 23 082D33322E36383832 083131362E38363532 0431302E30',DNSClass::IN,DNSType::GPOS));

        $this->assertInstanceOf(AAAA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('fe80::1',DNSClass::IN,DNSType::AAAA));
        $this->assertInstanceOf(AAAA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 16 FE800000000000000000000000000001',DNSClass::IN,DNSType::AAAA));

        //TODO LOC

        //TODO NXT

        //TODO EID

        //TODO NIMLOC

        $this->assertInstanceOf(SRV::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 0 0 service.example.com.',DNSClass::IN,DNSType::SRV));
        $this->assertInstanceOf(SRV::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 27 0001 0000 0000 0773657276696365076578616D706C6503636F6D00',DNSClass::IN,DNSType::SRV));

        //TODO ATMA

        //TODO NAPTR

        $this->assertInstanceOf(KX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 kx.example.com.',DNSClass::IN,DNSType::KX));
        $this->assertInstanceOf(KX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 18 000A 026B78076578616D706C6503636F6D00',DNSClass::IN,DNSType::KX));

        //TODO CERT

        //TODO A6

        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::DNAME));
        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::DNAME));

        //TODO SINK

        //TODO OPT

        //TODO APL

        $this->assertInstanceOf(DS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::DS));
        $this->assertInstanceOf(DS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 26 EC45 0005 0001 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::DS));

        //TODO SSHFP

        //TODO IPSECKEY

        //TODO RRSIG

        //TODO NSEC

        //TODO DNSKEY

        //TODO DHCID

        //TODO NSEC3

        //TODO NSEC3PARAM

        //TODO TLSA

        //TODO SMIMEA

        //TODO HIP

        //TODO NINFO

        //TODO RKEY

        //TODO TALINK

        //TODO CDS

        //TODO CDNSKEY

        //TODO OPENPGPKEY

        //TODO CSYNC

        //TODO ZONEMD

        //TODO SVCB

        //TODO HTTPS

        //TODO DSYNC

        //TODO SPF

        //TODO NID

        //TODO L32

        //TODO L64

        //TODO LP

        //TODO EUI48

        //TODO EUI64

        //TODO NXNAME

        //TODO TKEY

        //TODO TSIG

        //TODO URI

        //TODO CAA

        //TODO AVC

        //TODO DOA

        //TODO AMTRELAY

        //TODO RESINFO

        //TODO WALLET

        //TODO CLA

        //TODO TA

        //TODO DLV
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromWireFormatByClassAndType(): void{
        $this->assertInstanceOf(A::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('7F000001'),DNSClass::IN,DNSType::A));

        $this->assertInstanceOf(NS::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::NS));

        $this->assertInstanceOf(MD::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::MD));

        $this->assertInstanceOf(MF::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::MF));

        $this->assertInstanceOf(CNAME::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::CNAME));

        $this->assertInstanceOf(SOA::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076D61696C626F78076578616D706C6503636F6D00').hex2bin('076578616D706C6503636F6D00').hex2bin('00000001').hex2bin('00000002').hex2bin('00000003').hex2bin('00000004').hex2bin('00000005'),DNSClass::IN,DNSType::SOA));

        $this->assertInstanceOf(MB::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::MB));

        $this->assertInstanceOf(MG::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::MG));

        $this->assertInstanceOf(MR::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::MR));

        $this->assertInstanceOf(NULLType::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000102030405060708090A0B0C0D0E0F'),DNSClass::IN,DNSType::NULL));

        $this->assertInstanceOf(WKS::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('7F000001').hex2bin('06').hex2bin('000002'),DNSClass::IN,DNSType::WKS));

        $this->assertInstanceOf(PTR::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::PTR));

        $this->assertInstanceOf(HINFO::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('08536F6D6520435055').hex2bin('07536F6D65204F53'),DNSClass::IN,DNSType::HINFO));

        $this->assertInstanceOf(MINFO::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('07726D61696C6278076578616D706C6503636F6D00').hex2bin('07656D61696C6278076578616D706C6503636F6D00'),DNSClass::IN,DNSType::MINFO));

        $this->assertInstanceOf(MX::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000A').hex2bin('026D78076578616D706C6503636F6D00'),DNSClass::IN,DNSType::MX));

        $this->assertInstanceOf(TXT::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('06546578742031').hex2bin('06546578742032').hex2bin('06546578742033'),DNSClass::IN,DNSType::TXT));

        $this->assertInstanceOf(RP::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076D61696C626F78076578616D706C6503636F6D00').hex2bin('0474657874076578616D706C6503636F6D00'),DNSClass::IN,DNSType::RP));

        $this->assertInstanceOf(AFSDB::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0001').hex2bin('03616673076578616D706C6503636F6D00'),DNSClass::IN,DNSType::AFSDB));

        $this->assertInstanceOf(X25::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0C333131303631373030393536'),DNSClass::IN,DNSType::X25));

        $this->assertInstanceOf(ISDN::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('081508620280032170').hex2bin('03303034'),DNSClass::IN,DNSType::ISDN));

        $this->assertInstanceOf(RT::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0014').hex2bin('0572656C6179076578616D706C6503636F6D00'),DNSClass::IN,DNSType::RT));

        $this->assertInstanceOf(NSAP::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('47000580005A0000000001E133FFFFFF00016100'),DNSClass::IN,DNSType::NSAP));

        $this->assertInstanceOf(NSAP_PTR::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::NSAP_PTR));

        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::DNAME));
    }

}