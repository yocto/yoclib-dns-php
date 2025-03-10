<?php
namespace YOCLIB\DNS\Tests\Helpers;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSClass;
use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;

use YOCLIB\DNS\Helpers\TypeHelper;
use YOCLIB\DNS\Types\A;
use YOCLIB\DNS\Types\A6;
use YOCLIB\DNS\Types\AAAA;
use YOCLIB\DNS\Types\AFSDB;
use YOCLIB\DNS\Types\AMTRELAY;
use YOCLIB\DNS\Types\APL;
use YOCLIB\DNS\Types\ATMA;
use YOCLIB\DNS\Types\AVC;
use YOCLIB\DNS\Types\CAA;
use YOCLIB\DNS\Types\CDNSKEY;
use YOCLIB\DNS\Types\CDS;
use YOCLIB\DNS\Types\CERT;
use YOCLIB\DNS\Types\CLA;
use YOCLIB\DNS\Types\CNAME;
use YOCLIB\DNS\Types\CSYNC;
use YOCLIB\DNS\Types\DHCID;
use YOCLIB\DNS\Types\DLV;
use YOCLIB\DNS\Types\DNAME;
use YOCLIB\DNS\Types\DNSKEY;
use YOCLIB\DNS\Types\DOA;
use YOCLIB\DNS\Types\DS;
use YOCLIB\DNS\Types\DSYNC;
use YOCLIB\DNS\Types\EID;
use YOCLIB\DNS\Types\EUI48;
use YOCLIB\DNS\Types\EUI64;
use YOCLIB\DNS\Types\GPOS;
use YOCLIB\DNS\Types\HINFO;
use YOCLIB\DNS\Types\HIP;
use YOCLIB\DNS\Types\HTTPS;
use YOCLIB\DNS\Types\IPN;
use YOCLIB\DNS\Types\IPSECKEY;
use YOCLIB\DNS\Types\ISDN;
use YOCLIB\DNS\Types\KEY;
use YOCLIB\DNS\Types\KX;
use YOCLIB\DNS\Types\L32;
use YOCLIB\DNS\Types\L64;
use YOCLIB\DNS\Types\LOC;
use YOCLIB\DNS\Types\LP;
use YOCLIB\DNS\Types\MB;
use YOCLIB\DNS\Types\MD;
use YOCLIB\DNS\Types\MF;
use YOCLIB\DNS\Types\MG;
use YOCLIB\DNS\Types\MINFO;
use YOCLIB\DNS\Types\MR;
use YOCLIB\DNS\Types\MX;
use YOCLIB\DNS\Types\NAPTR;
use YOCLIB\DNS\Types\NID;
use YOCLIB\DNS\Types\NIMLOC;
use YOCLIB\DNS\Types\NINFO;
use YOCLIB\DNS\Types\NS;
use YOCLIB\DNS\Types\NSAP;
use YOCLIB\DNS\Types\NSAP_PTR;
use YOCLIB\DNS\Types\NSEC;
use YOCLIB\DNS\Types\NSEC3;
use YOCLIB\DNS\Types\NSEC3PARAM;
use YOCLIB\DNS\Types\NULLType;
use YOCLIB\DNS\Types\NXT;
use YOCLIB\DNS\Types\OPENPGPKEY;
use YOCLIB\DNS\Types\OPT;
use YOCLIB\DNS\Types\PTR;
use YOCLIB\DNS\Types\PX;
use YOCLIB\DNS\Types\RESINFO;
use YOCLIB\DNS\Types\RKEY;
use YOCLIB\DNS\Types\RP;
use YOCLIB\DNS\Types\RRSIG;
use YOCLIB\DNS\Types\RT;
use YOCLIB\DNS\Types\SIG;
use YOCLIB\DNS\Types\SINK;
use YOCLIB\DNS\Types\SMIMEA;
use YOCLIB\DNS\Types\SOA;
use YOCLIB\DNS\Types\SPF;
use YOCLIB\DNS\Types\SRV;
use YOCLIB\DNS\Types\SSHFP;
use YOCLIB\DNS\Types\SVCB;
use YOCLIB\DNS\Types\TA;
use YOCLIB\DNS\Types\TALINK;
use YOCLIB\DNS\Types\TKEY;
use YOCLIB\DNS\Types\TLSA;
use YOCLIB\DNS\Types\TSIG;
use YOCLIB\DNS\Types\TXT;
use YOCLIB\DNS\Types\URI;
use YOCLIB\DNS\Types\WALLET;
use YOCLIB\DNS\Types\WKS;
use YOCLIB\DNS\Types\X25;
use YOCLIB\DNS\Types\ZONEMD;

class TypeHelperTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testCompare(): void{
        $this->assertSame(0,TypeHelper::compare('.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A,'.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A));

        $this->assertSame(1,TypeHelper::compare('a.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A,'.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A));
        $this->assertSame(-1,TypeHelper::compare('.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A,'a.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A));

        $this->assertSame(-2,TypeHelper::compare('.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A,'.',"\x7F\x00\x00\x01",DNSClass::CH,DNSType::A));
        $this->assertSame(2,TypeHelper::compare('.',"\x7F\x00\x00\x01",DNSClass::CH,DNSType::A,'.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A));

        $this->assertSame(-1,TypeHelper::compare('.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A,'.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::NS));
        $this->assertSame(1,TypeHelper::compare('.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::NS,'.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A));

        $this->assertSame(-1,TypeHelper::compare('.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A,'.',"\x7F\x00\x00\x02",DNSClass::IN,DNSType::A));
        $this->assertSame(1,TypeHelper::compare('.',"\x7F\x00\x00\x02",DNSClass::IN,DNSType::A,'.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A));

        $this->assertSame(-1,TypeHelper::compare('.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A,'.',"\x7F\x00\x00\x01\xAA",DNSClass::IN,DNSType::A));
        $this->assertSame(1,TypeHelper::compare('.',"\x7F\x00\x00\x01\xAA",DNSClass::IN,DNSType::A,'.',"\x7F\x00\x00\x01",DNSClass::IN,DNSType::A));
    }

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

//        $this->assertInstanceOf(WKS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('127.0.0.1 6 SMTP',DNSClass::IN,DNSType::WKS));
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

        $this->assertInstanceOf(SIG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('30 1 2 19970102030405 19961211100908 2143 foo.nil. AIYADP8d3zYNyQwW2EM4wXVFdslEJcUx/fxkfBeH1El4ixPFhpfHFElxbvKoWmvjDTCmfiYy2X+8XpFjwICHc398kzWsTMKlxovpz2FnCTM=',DNSClass::IN,DNSType::SIG));
        $this->assertInstanceOf(SIG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 107 001E 01 02 00000E10 32CB25A5 32AE8844 085F 03666F6F036E696C00 0086000CFF1DDF360DC90C16D84338C1754576C94425C531FDFC647C1787D449788B13C58697C71449716EF2A85A6BE30D30A67E2632D97FBC5E9163C08087737F7C9335AC4CC2A5C68BE9CF61670933',DNSClass::IN,DNSType::SIG));

        $this->assertInstanceOf(KEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 1 1 dGVzdA==',DNSClass::IN,DNSType::KEY));
        $this->assertInstanceOf(KEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 9 0001 01 01 0474657374',DNSClass::IN,DNSType::KEY));

        $this->assertInstanceOf(PX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 map822.example.com. mapx400.example.com.',DNSClass::IN,DNSType::PX));
        $this->assertInstanceOf(PX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 43 000A 066D6170383232076578616D706C6503636F6D00 076D617078343030076578616D706C6503636F6D00',DNSClass::IN,DNSType::PX));

        $this->assertInstanceOf(GPOS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('-32.6882 116.8652 10.0',DNSClass::IN,DNSType::GPOS));
        $this->assertInstanceOf(GPOS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 23 082D33322E36383832 083131362E38363532 0431302E30',DNSClass::IN,DNSType::GPOS));

        $this->assertInstanceOf(AAAA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('fe80::1',DNSClass::IN,DNSType::AAAA));
        $this->assertInstanceOf(AAAA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 16 FE800000000000000000000000000001',DNSClass::IN,DNSType::AAAA));

        $this->assertInstanceOf(LOC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('42 21 43.952 N 71 5 6.344 W -24m 1m 200m',DNSClass::IN,DNSType::LOC));
        $this->assertInstanceOf(LOC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 16 00 00 00 00 01020304 01020304 01020304',DNSClass::IN,DNSType::LOC));

        $this->assertInstanceOf(NXT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('medium.foo.nil. A MX SIG NXT',DNSClass::IN,DNSType::NXT));
        $this->assertInstanceOf(NXT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 20 066D656469756D03666F6F036E696C00 02800041',DNSClass::IN,DNSType::NXT));

        $this->assertInstanceOf(EID::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('E32C 6F78 163A 9348',DNSClass::IN,DNSType::EID));
        $this->assertInstanceOf(EID::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 8 E32C6F78163A9348',DNSClass::IN,DNSType::EID));

        $this->assertInstanceOf(NIMLOC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('752341 59EAC4 5780 0920',DNSClass::IN,DNSType::NIMLOC));
        $this->assertInstanceOf(NIMLOC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 10 75234159EAC457800920',DNSClass::IN,DNSType::NIMLOC));

        $this->assertInstanceOf(SRV::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 0 0 service.example.com.',DNSClass::IN,DNSType::SRV));
        $this->assertInstanceOf(SRV::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 27 0001 0000 0000 0773657276696365076578616D706C6503636F6D00',DNSClass::IN,DNSType::SRV));

//        $this->assertInstanceOf(ATMA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::ATMA));
//        $this->assertInstanceOf(ATMA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::ATMA));

        $this->assertInstanceOf(NAPTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('100 50 "s" "http" "" www.example.com.',DNSClass::IN,DNSType::NAPTR));
        $this->assertInstanceOf(NAPTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 29 0064 0032 0173 0468747470 00 03777777076578616D706C6503636F6D00',DNSClass::IN,DNSType::NAPTR));

        $this->assertInstanceOf(KX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 kx.example.com.',DNSClass::IN,DNSType::KX));
        $this->assertInstanceOf(KX::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 18 000A 026B78076578616D706C6503636F6D00',DNSClass::IN,DNSType::KX));

        $this->assertInstanceOf(CERT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('3 0 0 AABBCCDD',DNSClass::IN,DNSType::CERT));
        $this->assertInstanceOf(CERT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 10 0003 0000 0000 AABBCCDD',DNSClass::IN,DNSType::CERT));

//        $this->assertInstanceOf(A6::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::A6));
//        $this->assertInstanceOf(A6::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::A6));

        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::DNAME));
        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::DNAME));

        $this->assertInstanceOf(SINK::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('65 2 dGVzdA==',DNSClass::IN,DNSType::SINK));
        $this->assertInstanceOf(SINK::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 8 0041 0002 74657374',DNSClass::IN,DNSType::SINK));

//        $this->assertInstanceOf(OPT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::OPT));
//        $this->assertInstanceOf(OPT::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::OPT));

//        $this->assertInstanceOf(APL::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::APL));
//        $this->assertInstanceOf(APL::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::APL));

        $this->assertInstanceOf(DS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::DS));
        $this->assertInstanceOf(DS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 26 EC45 0005 0001 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::DS));

        $this->assertInstanceOf(SSHFP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('2 1 123456789abcdef67890123456789abcdef67890',DNSClass::IN,DNSType::SSHFP));
        $this->assertInstanceOf(SSHFP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 22 02 01 123456789ABCDEF67890123456789ABCDEF67890',DNSClass::IN,DNSType::SSHFP));

        $this->assertInstanceOf(IPSECKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 1 2 192.0.2.38 AQNRU3mG7TVTO2BkR47usntb102uFJtugbo6BSGvgqt4AQ==',DNSClass::IN,DNSType::IPSECKEY));
        $this->assertInstanceOf(IPSECKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 41 0A 01 02 C0000226 010351537986ED35533B6064478EEEB27B5BD74DAE149B6E81BA3A0521AF82AB7801',DNSClass::IN,DNSType::IPSECKEY));

        $this->assertInstanceOf(RRSIG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 5 3 86400 20030322173103 20030220173103 2642 example.com. oJB1W6WNGv+ldvQ3WDG0MQkg5IEhjRip8WTrPYGv07h108dUKGMeDPKijVCHX3DDKdfb+v6oB9wfuh3DTJXUAfI/M0zmO/zz8bW0Rznl8O3tGNazPwQKkRN20XPXV6nwwfoXmJQbsLNrLfkGJ5D6fwFm8nN+6pBzeDQfsS3Ap3o=',DNSClass::IN,DNSType::RRSIG));
        $this->assertInstanceOf(RRSIG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 159 0001 05 03 00015180 00001234 00001234 0A52 076578616D706C6503636F6D00 A090755BA58D1AFFA576F4375831B4310920E481218D18A9F164EB3D81AFD3B875D3C75428631E0CF2A28D50875F70C329D7DBFAFEA807DC1FBA1DC34C95D401F23F334CE63BFCF3F1B5B44739E5F0EDED18D6B33F040A911376D173D757A9F0C1FA1798941BB0B36B2DF9062790FA7F0166F2737EEA907378341FB12DC0A77A',DNSClass::IN,DNSType::RRSIG));

        $this->assertInstanceOf(NSEC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('host.example.com. A MX RRSIG NSEC TYPE1234',DNSClass::IN,DNSType::NSEC));
        $this->assertInstanceOf(NSEC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 55 04686F7374076578616D706C6503636F6D00 0006400100000003041B000000000000000000000000000000000000000000000000000020',DNSClass::IN,DNSType::NSEC));

        $this->assertInstanceOf(DNSKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 1 1 dGVzdA==',DNSClass::IN,DNSType::DNSKEY));
        $this->assertInstanceOf(DNSKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 9 0001 01 01 0474657374',DNSClass::IN,DNSType::DNSKEY));

        $this->assertInstanceOf(DHCID::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('AAIBY2/AuCccgoJbsaxcQc9TUapptP69lOjxfNuVAA2kjEA=',DNSClass::IN,DNSType::DHCID));
        $this->assertInstanceOf(DHCID::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 35 000201636FC0B8271C82825BB1AC5C41CF5351AA69B4FEBD94E8F17CDB95000DA48C40',DNSClass::IN,DNSType::DHCID));

        $this->assertInstanceOf(NSEC3::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 1 12 aabbccdd 2vptu5timamqttgl4luu9kg21e0aor3s A RRSIG',DNSClass::IN,DNSType::NSEC3));
        $this->assertInstanceOf(NSEC3::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 54 01 01 000C 086161626263636464 2032767074753574696D616D717474676C346C7575396B6732316530616F723373 0006400000000002',DNSClass::IN,DNSType::NSEC3));

        $this->assertInstanceOf(NSEC3PARAM::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 0 12 aabbccdd',DNSClass::IN,DNSType::NSEC3PARAM));
        $this->assertInstanceOf(NSEC3PARAM::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 9 01 00 000C 04AABBCCDD',DNSClass::IN,DNSType::NSEC3PARAM));

        $this->assertInstanceOf(TLSA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('0 0 1 d2abde240d7cd3ee6b4b28c54df034b97983a1d16e8a410e4561cb106618e971',DNSClass::IN,DNSType::TLSA));
        $this->assertInstanceOf(TLSA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 35 00 00 01 D2ABDE240D7CD3EE6B4B28C54DF034B97983A1D16E8A410E4561CB106618E971',DNSClass::IN,DNSType::TLSA));

        $this->assertInstanceOf(SMIMEA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('0 0 1 d2abde240d7cd3ee6b4b28c54df034b97983a1d16e8a410e4561cb106618e971',DNSClass::IN,DNSType::SMIMEA));
        $this->assertInstanceOf(SMIMEA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 35 00 00 01 D2ABDE240D7CD3EE6B4B28C54DF034B97983A1D16E8A410E4561CB106618E971',DNSClass::IN,DNSType::SMIMEA));

//        $this->assertInstanceOf(HIP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::HIP));
//        $this->assertInstanceOf(HIP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::HIP));

        $this->assertInstanceOf(NINFO::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('"Healthy"',DNSClass::IN,DNSType::NINFO));
        $this->assertInstanceOf(NINFO::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 8 074865616C746879',DNSClass::IN,DNSType::NINFO));

        $this->assertInstanceOf(RKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 1 1 dGVzdA==',DNSClass::IN,DNSType::RKEY));
        $this->assertInstanceOf(RKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 9 0001 01 01 0474657374',DNSClass::IN,DNSType::RKEY));

        $this->assertInstanceOf(TALINK::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('prev.example.com. next.example.com.',DNSClass::IN,DNSType::TALINK));
        $this->assertInstanceOf(TALINK::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 36 0470726576076578616D706C6503636F6D00 046E657874076578616D706C6503636F6D00',DNSClass::IN,DNSType::TALINK));

        $this->assertInstanceOf(CDS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::CDS));
        $this->assertInstanceOf(CDS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 26 EC45 0005 0001 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::CDS));

        $this->assertInstanceOf(CDNSKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('1 1 1 dGVzdA==',DNSClass::IN,DNSType::CDNSKEY));
        $this->assertInstanceOf(CDNSKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 9 0001 01 01 0474657374',DNSClass::IN,DNSType::CDNSKEY));

        $this->assertInstanceOf(OPENPGPKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('dGVzdA==',DNSClass::IN,DNSType::OPENPGPKEY));
        $this->assertInstanceOf(OPENPGPKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 4 74657374',DNSClass::IN,DNSType::OPENPGPKEY));

        $this->assertInstanceOf(CSYNC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('66 3 A NS AAAA',DNSClass::IN,DNSType::CSYNC));
        $this->assertInstanceOf(CSYNC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 10 00000042 0003 06000010',DNSClass::IN,DNSType::CSYNC));

        $this->assertInstanceOf(ZONEMD::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('2018031500 1 1 FEBE3D4CE2EC2FFA4BA99D46CD69D6D29711E55217057BEE7EB1A7B641A47BA7FED2DD5B97AE499FAFA4F22C6BD647DE',DNSClass::IN,DNSType::ZONEMD));
        $this->assertInstanceOf(ZONEMD::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 54 7848B78C 01 01 FEBE3D4CE2EC2FFA4BA99D46CD69D6D29711E55217057BEE7EB1A7B641A47BA7FED2DD5B97AE499FAFA4F22C6BD647DE',DNSClass::IN,DNSType::ZONEMD));

//        $this->assertInstanceOf(SVCB::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::SVCB));
//        $this->assertInstanceOf(SVCB::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::SVCB));

//        $this->assertInstanceOf(HTTPS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::HTTPS));
//        $this->assertInstanceOf(HTTPS::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::HTTPS));

//        $this->assertInstanceOf(DSYNC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::DSYNC));
//        $this->assertInstanceOf(DSYNC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::DSYNC));

        $this->assertInstanceOf(SPF::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('"v=spf1 -all"',DNSClass::IN,DNSType::SPF));
        $this->assertInstanceOf(SPF::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 12 0B763D73706631202D616C6C',DNSClass::IN,DNSType::SPF));

        $this->assertInstanceOf(NID::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 0014:4fff:ff20:ee64',DNSClass::IN,DNSType::NID));
        $this->assertInstanceOf(NID::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 10 000A 00144FFFFF20EE64',DNSClass::IN,DNSType::NID));

        $this->assertInstanceOf(L32::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 10.1.2.0',DNSClass::IN,DNSType::L32));
        $this->assertInstanceOf(L32::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 6 000A 0A010200',DNSClass::IN,DNSType::L32));

        $this->assertInstanceOf(L64::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 2001:0DB8:1140:1000',DNSClass::IN,DNSType::L64));
        $this->assertInstanceOf(L64::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 10 000A 20010DB811401000',DNSClass::IN,DNSType::L64));

        $this->assertInstanceOf(LP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 lp.example.com.',DNSClass::IN,DNSType::LP));
        $this->assertInstanceOf(LP::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 18 000A 026C70076578616D706C6503636F6D00',DNSClass::IN,DNSType::LP));

        $this->assertInstanceOf(EUI48::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('00-00-5e-00-53-2a',DNSClass::IN,DNSType::EUI48));
        $this->assertInstanceOf(EUI48::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 6 00005E00532A',DNSClass::IN,DNSType::EUI48));

        $this->assertInstanceOf(EUI64::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('00-00-5e-ef-10-00-00-2a',DNSClass::IN,DNSType::EUI64));
        $this->assertInstanceOf(EUI64::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 8 00005EEF1000002A',DNSClass::IN,DNSType::EUI64));

        // NXNAME: Meta-Type

//        $this->assertInstanceOf(TKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::TKEY));
//        $this->assertInstanceOf(TKEY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::TKEY));

//        $this->assertInstanceOf(TSIG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::TSIG));
//        $this->assertInstanceOf(TSIG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::TSIG));

        // IXFR: QType

        // AXFR: QType

        // MAILB: QType

        // MAILA: QType

        // ANY: QType

        $this->assertInstanceOf(URI::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('10 1 "https://www.example.com/path"',DNSClass::IN,DNSType::URI));
        $this->assertInstanceOf(URI::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 33 000A 0001 1C68747470733A2F2F7777772E6578616D706C652E636F6D2F70617468',DNSClass::IN,DNSType::URI));

        $this->assertInstanceOf(CAA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('0 issue "ca1.example.net"',DNSClass::IN,DNSType::CAA));
        $this->assertInstanceOf(CAA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 22 00 056973737565 6361312E6578616D706C652E6E6574',DNSClass::IN,DNSType::CAA));

        $this->assertInstanceOf(AVC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('"app-name:WOLFGANG|app-class:OAM|business=yes"',DNSClass::IN,DNSType::AVC));
        $this->assertInstanceOf(AVC::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 45 2C6170702D6E616D653A574F4C4647414E477C6170702D636C6173733A4F414D7C627573696E6573733D796573',DNSClass::IN,DNSType::AVC));

        $this->assertInstanceOf(DOA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('4294967295 4294967295 255 "text/plain" dGVzdA==',DNSClass::IN,DNSType::DOA));
        $this->assertInstanceOf(DOA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 24 FFFFFFFF FFFFFFFF FF 0A746578742F706C61696E 74657374',DNSClass::IN,DNSType::DOA));

//        $this->assertInstanceOf(AMTRELAY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('',DNSClass::IN,DNSType::AMTRELAY));
//        $this->assertInstanceOf(AMTRELAY::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 0',DNSClass::IN,DNSType::AMTRELAY));

        $this->assertInstanceOf(RESINFO::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('qnamemin exterr=15-17 infourl=https://resolver.example.com/guide',DNSClass::IN,DNSType::RESINFO));
        $this->assertInstanceOf(RESINFO::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 65 08716E616D656D696E 0C6578746572723D31352D3137 2A696E666F75726C3D68747470733A2F2F7265736F6C7665722E6578616D706C652E636F6D2F6775696465',DNSClass::IN,DNSType::RESINFO));

        $this->assertInstanceOf(WALLET::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('"BTC" "bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh"',DNSClass::IN,DNSType::WALLET));
        $this->assertInstanceOf(WALLET::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 47 03425443 2A626331717879326B676479676A727371747A71326E30797266323439337038336B6B666A687830776C68',DNSClass::IN,DNSType::WALLET));

        $this->assertInstanceOf(CLA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('"TCP-v4-v6"',DNSClass::IN,DNSType::CLA));
        $this->assertInstanceOf(CLA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 10 095443502D76342D7636',DNSClass::IN,DNSType::CLA));

        $this->assertInstanceOf(IPN::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('18446744073709551615',DNSClass::IN,DNSType::IPN));
        $this->assertInstanceOf(IPN::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('4294967295.4294967295',DNSClass::IN,DNSType::IPN));
        $this->assertInstanceOf(IPN::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 8 FFFFFFFFFFFFFFFF',DNSClass::IN,DNSType::IPN));

        $this->assertInstanceOf(TA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::TA));
        $this->assertInstanceOf(TA::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 26 EC45 0005 0001 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::TA));

        $this->assertInstanceOf(DLV::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('60485 5 1 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::DLV));
        $this->assertInstanceOf(DLV::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 26 EC45 0005 0001 2BB183AF5F22588179A53B0A98631FAD1A292118',DNSClass::IN,DNSType::DLV));
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatByClassAndTypeZeroClass(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Class cannot be zero.');

        TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 4 AABBCCDD',0,DNSType::A);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatByClassAndTypeZeroType(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Type cannot be zero.');

        TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 4 AABBCCDD',DNSClass::IN,0);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatByClassAndTypeUnsupported(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Trying to deserialize an unsupported type from presentation format.');

        TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 4 AABBCCDD',DNSClass::IN,-1);
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

        $this->assertInstanceOf(SIG::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('001E').hex2bin('01').hex2bin('02').hex2bin('00000E10').hex2bin('32CB25A5').hex2bin('32AE8844').hex2bin('085F').hex2bin('03666F6F036E696C00').hex2bin('0086000CFF1DDF360DC90C16D84338C1754576C94425C531FDFC647C1787D449788B13C58697C71449716EF2A85A6BE30D30A67E2632D97FBC5E9163C08087737F7C9335AC4CC2A5C68BE9CF61670933'),DNSClass::IN,DNSType::SIG));

        $this->assertInstanceOf(KEY::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0001').hex2bin('01').hex2bin('01').hex2bin('0474657374'),DNSClass::IN,DNSType::KEY));

        $this->assertInstanceOf(PX::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000A').hex2bin('066D6170383232076578616D706C6503636F6D00').hex2bin('076D617078343030076578616D706C6503636F6D00'),DNSClass::IN,DNSType::PX));

        $this->assertInstanceOf(GPOS::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('082D33322E36383832').hex2bin('083131362E38363532').hex2bin('0431302E30'),DNSClass::IN,DNSType::GPOS));

        $this->assertInstanceOf(AAAA::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('FE800000000000000000000000000001'),DNSClass::IN,DNSType::AAAA));

        $this->assertInstanceOf(LOC::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('00').hex2bin('00').hex2bin('00').hex2bin('00').hex2bin('01020304').hex2bin('01020304').hex2bin('01020304'),DNSClass::IN,DNSType::LOC));

        $this->assertInstanceOf(NXT::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('066D656469756D03666F6F036E696C00').hex2bin('02800041'),DNSClass::IN,DNSType::NXT));

        $this->assertInstanceOf(EID::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('E32C6F78163A9348'),DNSClass::IN,DNSType::EID));

        $this->assertInstanceOf(NIMLOC::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('75234159EAC457800920'),DNSClass::IN,DNSType::NIMLOC));

        $this->assertInstanceOf(SRV::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0001').hex2bin('0000').hex2bin('0000').hex2bin('0773657276696365076578616D706C6503636F6D00'),DNSClass::IN,DNSType::SRV));

//        $this->assertInstanceOf(ATMA::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::ATMA));

        $this->assertInstanceOf(NAPTR::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0064').hex2bin('0032').hex2bin('0173').hex2bin('0468747470').hex2bin('00').hex2bin('03777777076578616D706C6503636F6D00'),DNSClass::IN,DNSType::NAPTR));

        $this->assertInstanceOf(KX::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000A').hex2bin('026B78076578616D706C6503636F6D00'),DNSClass::IN,DNSType::KX));

        $this->assertInstanceOf(CERT::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0003').hex2bin('0000').hex2bin('0000').hex2bin('AABBCCDD'),DNSClass::IN,DNSType::CERT));

//        $this->assertInstanceOf(A6::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::A6));

        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('076578616D706C6503636F6D00'),DNSClass::IN,DNSType::DNAME));

        $this->assertInstanceOf(SINK::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0041').hex2bin('0002').hex2bin('74657374'),DNSClass::IN,DNSType::SINK));

//        $this->assertInstanceOf(OPT::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::OPT));

//        $this->assertInstanceOf(APL::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::APL));

        $this->assertInstanceOf(DS::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('EC45').hex2bin('0005').hex2bin('0001').hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118'),DNSClass::IN,DNSType::DS));

        $this->assertInstanceOf(SSHFP::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('02').hex2bin('01').hex2bin('123456789ABCDEF67890123456789ABCDEF67890'),DNSClass::IN,DNSType::SSHFP));

        $this->assertInstanceOf(IPSECKEY::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0A').hex2bin('01').hex2bin('02').hex2bin('C0000226').hex2bin('010351537986ED35533B6064478EEEB27B5BD74DAE149B6E81BA3A0521AF82AB7801'),DNSClass::IN,DNSType::IPSECKEY));

        $this->assertInstanceOf(RRSIG::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0001').hex2bin('05').hex2bin('03').hex2bin('00015180').hex2bin('00001234').hex2bin('00001234').hex2bin('0A52').hex2bin('076578616D706C6503636F6D00').hex2bin('A090755BA58D1AFFA576F4375831B4310920E481218D18A9F164EB3D81AFD3B875D3C75428631E0CF2A28D50875F70C329D7DBFAFEA807DC1FBA1DC34C95D401F23F334CE63BFCF3F1B5B44739E5F0EDED18D6B33F040A911376D173D757A9F0C1FA1798941BB0B36B2DF9062790FA7F0166F2737EEA907378341FB12DC0A77A'),DNSClass::IN,DNSType::RRSIG));

        $this->assertInstanceOf(NSEC::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('04686F7374076578616D706C6503636F6D00').hex2bin('0006400100000003041B000000000000000000000000000000000000000000000000000020'),DNSClass::IN,DNSType::NSEC));

        $this->assertInstanceOf(DNSKEY::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0001').hex2bin('01').hex2bin('01').hex2bin('0474657374'),DNSClass::IN,DNSType::DNSKEY));

        $this->assertInstanceOf(DHCID::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000201636FC0B8271C82825BB1AC5C41CF5351AA69B4FEBD94E8F17CDB95000DA48C40'),DNSClass::IN,DNSType::DHCID));

        $this->assertInstanceOf(NSEC3::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('01').hex2bin('01').hex2bin('000C').hex2bin('086161626263636464').hex2bin('2032767074753574696D616D717474676C346C7575396B6732316530616F723373').hex2bin('0006400000000002'),DNSClass::IN,DNSType::NSEC3));

        $this->assertInstanceOf(NSEC3PARAM::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('01').hex2bin('00').hex2bin('000C').hex2bin('04AABBCCDD'),DNSClass::IN,DNSType::NSEC3PARAM));

        $this->assertInstanceOf(TLSA::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('00').hex2bin('00').hex2bin('01').hex2bin('D2ABDE240D7CD3EE6B4B28C54DF034B97983A1D16E8A410E4561CB106618E971'),DNSClass::IN,DNSType::TLSA));

        $this->assertInstanceOf(SMIMEA::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('00').hex2bin('00').hex2bin('01').hex2bin('D2ABDE240D7CD3EE6B4B28C54DF034B97983A1D16E8A410E4561CB106618E971'),DNSClass::IN,DNSType::SMIMEA));

//        $this->assertInstanceOf(HIP::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::HIP));

        $this->assertInstanceOf(NINFO::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('074865616C746879'),DNSClass::IN,DNSType::NINFO));

        $this->assertInstanceOf(RKEY::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0001').hex2bin('01').hex2bin('01').hex2bin('0474657374'),DNSClass::IN,DNSType::RKEY));

        $this->assertInstanceOf(TALINK::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0470726576076578616D706C6503636F6D00').hex2bin('046E657874076578616D706C6503636F6D00'),DNSClass::IN,DNSType::TALINK));

        $this->assertInstanceOf(CDS::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('EC45').hex2bin('0005').hex2bin('0001').hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118'),DNSClass::IN,DNSType::CDS));

        $this->assertInstanceOf(CDNSKEY::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0001').hex2bin('01').hex2bin('01').hex2bin('0474657374'),DNSClass::IN,DNSType::CDNSKEY));

        $this->assertInstanceOf(OPENPGPKEY::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('74657374'),DNSClass::IN,DNSType::OPENPGPKEY));

        $this->assertInstanceOf(CSYNC::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('00000042').hex2bin('0003').hex2bin('06000010'),DNSClass::IN,DNSType::CSYNC));

        $this->assertInstanceOf(ZONEMD::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('7848B78C').hex2bin('01').hex2bin('01').hex2bin('FEBE3D4CE2EC2FFA4BA99D46CD69D6D29711E55217057BEE7EB1A7B641A47BA7FED2DD5B97AE499FAFA4F22C6BD647DE'),DNSClass::IN,DNSType::ZONEMD));

//        $this->assertInstanceOf(SVCB::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::SVCB));

//        $this->assertInstanceOf(HTTPS::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::HTTPS));

//        $this->assertInstanceOf(DSYNC::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::DSYNC));

        $this->assertInstanceOf(SPF::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('0B763D73706631202D616C6C'),DNSClass::IN,DNSType::SPF));

        $this->assertInstanceOf(NID::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000A').hex2bin('00144FFFFF20EE64'),DNSClass::IN,DNSType::NID));

        $this->assertInstanceOf(L32::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000A').hex2bin('0A010200'),DNSClass::IN,DNSType::L32));

        $this->assertInstanceOf(L64::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000A').hex2bin('20010DB811401000'),DNSClass::IN,DNSType::L64));

        $this->assertInstanceOf(LP::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000A').hex2bin('026C70076578616D706C6503636F6D00'),DNSClass::IN,DNSType::LP));

        $this->assertInstanceOf(EUI48::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('00005E00532A'),DNSClass::IN,DNSType::EUI48));

        $this->assertInstanceOf(EUI64::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('00005EEF1000002A'),DNSClass::IN,DNSType::EUI64));

        // NXNAME: Meta-Type

//        $this->assertInstanceOf(TKEY::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::TKEY));

//        $this->assertInstanceOf(TSIG::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::TSIG));

        // IXFR: QType

        // AXFR: QType

        // MAILB: QType

        // MAILA: QType

        // ANY: QType

        $this->assertInstanceOf(URI::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('000A').hex2bin('0001').hex2bin('1C68747470733A2F2F7777772E6578616D706C652E636F6D2F70617468'),DNSClass::IN,DNSType::URI));

        $this->assertInstanceOf(CAA::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('00').hex2bin('056973737565').hex2bin('6361312E6578616D706C652E6E6574'),DNSClass::IN,DNSType::CAA));

        $this->assertInstanceOf(AVC::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('2C6170702D6E616D653A574F4C4647414E477C6170702D636C6173733A4F414D7C627573696E6573733D796573'),DNSClass::IN,DNSType::AVC));

        $this->assertInstanceOf(DOA::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('FFFFFFFF').hex2bin('FFFFFFFF').hex2bin('FF').hex2bin('0A746578742F706C61696E').hex2bin('74657374'),DNSClass::IN,DNSType::DOA));

//        $this->assertInstanceOf(AMTRELAY::class,TypeHelper::deserializeFromWireFormatByClassAndType('',DNSClass::IN,DNSType::AMTRELAY));

        $this->assertInstanceOf(RESINFO::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('08716E616D656D696E').hex2bin('0C6578746572723D31352D3137').hex2bin('2A696E666F75726C3D68747470733A2F2F7265736F6C7665722E6578616D706C652E636F6D2F6775696465'),DNSClass::IN,DNSType::RESINFO));

        $this->assertInstanceOf(WALLET::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('03425443').hex2bin('2A626331717879326B676479676A727371747A71326E30797266323439337038336B6B666A687830776C68'),DNSClass::IN,DNSType::WALLET));

        $this->assertInstanceOf(CLA::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('095443502D76342D7636'),DNSClass::IN,DNSType::CLA));

        $this->assertInstanceOf(IPN::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('FFFFFFFFFFFFFFFF'),DNSClass::IN,DNSType::IPN));

        $this->assertInstanceOf(TA::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('EC45').hex2bin('0005').hex2bin('0001').hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118'),DNSClass::IN,DNSType::TA));

        $this->assertInstanceOf(DLV::class,TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('EC45').hex2bin('0005').hex2bin('0001').hex2bin('2BB183AF5F22588179A53B0A98631FAD1A292118'),DNSClass::IN,DNSType::DLV));
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromWireFormatByClassAndTypeZeroClass(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Class cannot be zero.');

        TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('AABBCCDD'),0,DNSType::A);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromWireFormatByClassAndTypeZeroType(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Type cannot be zero.');

        TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('AABBCCDD'),DNSClass::IN,0);
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromWireFormatByClassAndTypeUnsupported(): void{
        self::expectException(DNSTypeException::class);
        self::expectExceptionMessage('Trying to deserialize an unsupported type from wire format.');

        TypeHelper::deserializeFromWireFormatByClassAndType(hex2bin('AABBCCDD'),DNSClass::IN,-1);
    }

}