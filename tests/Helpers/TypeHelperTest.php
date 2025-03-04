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
use YOCLIB\DNS\Types\MB;
use YOCLIB\DNS\Types\MD;
use YOCLIB\DNS\Types\MF;
use YOCLIB\DNS\Types\MG;
use YOCLIB\DNS\Types\MR;
use YOCLIB\DNS\Types\NS;
use YOCLIB\DNS\Types\NSAP_PTR;
use YOCLIB\DNS\Types\PTR;

class TypeHelperTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSTypeException
     */
    public function testDeserializeFromPresentationFormatByClassAndType(){
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

        $this->assertInstanceOf(MB::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::MB));
        $this->assertInstanceOf(MB::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::MB));

        $this->assertInstanceOf(MG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::MG));
        $this->assertInstanceOf(MG::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::MG));

        $this->assertInstanceOf(MR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::MR));
        $this->assertInstanceOf(MR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::MR));

        $this->assertInstanceOf(PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::PTR));
        $this->assertInstanceOf(PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::PTR));

        $this->assertInstanceOf(NSAP_PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::NSAP_PTR));
        $this->assertInstanceOf(NSAP_PTR::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::NSAP_PTR));

        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('example.com.',DNSClass::IN,DNSType::DNAME));
        $this->assertInstanceOf(DNAME::class,TypeHelper::deserializeFromPresentationFormatByClassAndType('\# 13 076578616D706C6503636F6D00',DNSClass::IN,DNSType::DNAME));


    }

}