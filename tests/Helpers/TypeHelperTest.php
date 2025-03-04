<?php
namespace YOCLIB\DNS\Tests\Helpers;

use PHPUnit\Framework\TestCase;
use YOCLIB\DNS\DNSClass;
use YOCLIB\DNS\DNSType;
use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Helpers\TypeHelper;
use YOCLIB\DNS\Types\A;
use YOCLIB\DNS\Types\NS;

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
    }

}