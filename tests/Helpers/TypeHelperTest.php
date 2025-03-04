<?php
namespace YOCLIB\DNS\Tests\Helpers;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\DNSClass;
use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Helpers\TypeHelper;
use YOCLIB\DNS\Types\A;
use YOCLIB\DNS\Types\CNAME;
use YOCLIB\DNS\Types\DNAME;
use YOCLIB\DNS\Types\HINFO;
use YOCLIB\DNS\Types\MB;
use YOCLIB\DNS\Types\MD;
use YOCLIB\DNS\Types\MF;
use YOCLIB\DNS\Types\MG;
use YOCLIB\DNS\Types\MINFO;
use YOCLIB\DNS\Types\MR;
use YOCLIB\DNS\Types\MX;
use YOCLIB\DNS\Types\NS;
use YOCLIB\DNS\Types\NSAP_PTR;
use YOCLIB\DNS\Types\NULLType;
use YOCLIB\DNS\Types\PTR;
use YOCLIB\DNS\Types\SOA;
use YOCLIB\DNS\Types\TXT;
use YOCLIB\DNS\Types\WKS;

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

        //TODO RP

        //TODO AFSDB

        //TODO X25

        //TODO ISDN

        //TODO RT

        //TODO NSAP

        $this->assertInstanceOf(NSAP_PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::NSAP_PTR));
        $this->assertInstanceOf(NSAP_PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::NSAP_PTR));

        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::DNAME));
        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::DNAME));
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
    }

}