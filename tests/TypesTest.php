<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\FQDN;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Types\A;
use YOCLIB\DNS\Types\NS;

class TypesTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException|DNSTypeException
     */
    public function testTypes(){
        $aRecord = new A([new IPv4Address('1.2.3.4')]);
        self::assertEquals('1.2.3.4',$aRecord->serializeToPresentationFormat());
        self::assertEquals("\x01\x02\x03\x04",$aRecord->serializeToWireFormat());

        $nsRecord = new NS([new FQDN(['ns','example','com',''])]);
        self::assertEquals('ns.example.com.',$nsRecord->serializeToPresentationFormat());
        self::assertEquals("\x02ns\x07example\x03com\x00",$nsRecord->serializeToWireFormat());

        $mdRecord = new NS([new FQDN(['@'])]);
        self::assertEquals('@',$mdRecord->serializeToPresentationFormat());
        self::assertEquals("\x01@\x40",$mdRecord->serializeToWireFormat());
    }

}