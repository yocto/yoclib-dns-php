<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\Bitmap;
use YOCLIB\DNS\Fields\CharacterString;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Fields\IPv6Address;
use YOCLIB\DNS\Fields\UnsignedInteger32;
use YOCLIB\DNS\Fields\UnsignedInteger8;
use YOCLIB\DNS\Types\A;
use YOCLIB\DNS\Types\AAAA;
use YOCLIB\DNS\Types\HINFO;
use YOCLIB\DNS\Types\MD;
use YOCLIB\DNS\Types\MINFO;
use YOCLIB\DNS\Types\NS;
use YOCLIB\DNS\Types\SOA;
use YOCLIB\DNS\Types\WKS;

class TypesTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException|DNSTypeException
     */
    public function testTypes(){
        $aRecord = new A([
            new IPv4Address('1.2.3.4'),
        ]);
        self::assertEquals('1.2.3.4',$aRecord->serializeToPresentationFormat());
        self::assertEquals("\x01\x02\x03\x04",$aRecord->serializeToWireFormat());

        $nsRecord = new NS([
            new FQDN('ns','example','com',''),
        ]);
        self::assertEquals('ns.example.com.',$nsRecord->serializeToPresentationFormat());
        self::assertEquals("\x02ns\x07example\x03com\x00",$nsRecord->serializeToWireFormat());

        $mdRecord = new MD([
            new FQDN('@'),
        ]);
        self::assertEquals('@',$mdRecord->serializeToPresentationFormat());
        self::assertEquals("\x01@\x40",$mdRecord->serializeToWireFormat());

        $soaRecord = new SOA([
            new FQDN('ns','example','com',''),
            new FQDN('my.dotted.mail.address','example','com',''),
            new UnsignedInteger32(123),
            new UnsignedInteger32(456),
            new UnsignedInteger32(789),
            new UnsignedInteger32(1011),
            new UnsignedInteger32(1213),
        ]);
        self::assertEquals('ns.example.com. my\.dotted\.mail\.address.example.com. 123 456 789 1011 1213',$soaRecord->serializeToPresentationFormat());
        self::assertEquals("\x02ns\x07example\x03com\x00"."\x16my.dotted.mail.address\x07example\x03com\x00"."\x00\x00\x00\x7B"."\x00\x00\x01\xC8"."\x00\x00\x03\x15"."\x00\x00\x03\xF3"."\x00\x00\x04\xBD",$soaRecord->serializeToWireFormat());

        $wksRecord = new WKS([
            new IPv4Address('1.2.3.4'),
            new UnsignedInteger8(6),
            new Bitmap([25]),
        ]);
        self::assertEquals('1.2.3.4 6 SMTP',$wksRecord->serializeToPresentationFormat());
        self::assertEquals("\x01\x02\x03\x04"."\x06"."\x00\x00\x00\x02",$wksRecord->serializeToWireFormat());

        $hinfoRecord = new HINFO([
            new CharacterString('ARM64'),
            new CharacterString('Linux (2025)'),
        ]);
        self::assertEquals('ARM64 "Linux (2025)"',$hinfoRecord->serializeToPresentationFormat());
        self::assertEquals("\x05ARM64"."\x0CLinux (2025)",$hinfoRecord->serializeToWireFormat());

        $minfoRecord = new MINFO([
            new FQDN('my.dotted.response.mail.address','example','com',''),
            new FQDN('my.dotted.error.mail.address','example','com',''),
        ]);
        self::assertEquals('my\.dotted\.response\.mail\.address.example.com. my\.dotted\.error\.mail\.address.example.com.',$minfoRecord->serializeToPresentationFormat());
        self::assertEquals("\x1Fmy.dotted.response.mail.address\x07example\x03com\x00"."\x1Cmy.dotted.error.mail.address\x07example\x03com\x00",$minfoRecord->serializeToWireFormat());

        $aaaaRecord = new AAAA([
            new IPv6Address('::'),
        ]);
        self::assertEquals('::',$aaaaRecord->serializeToPresentationFormat());
        self::assertEquals("\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00",$aaaaRecord->serializeToWireFormat());
    }

}