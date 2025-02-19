<?php
namespace YOCLIB\DNS\Tests;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSTypeException;
use YOCLIB\DNS\Fields\IPv4Address;
use YOCLIB\DNS\Types\A;

class TypesTest extends TestCase{

    /**
     * @return void
     * @throws DNSTypeException
     */
    public function testTypes(){
        self::assertEquals('1.2.3.4',(new A([new IPv4Address('1.2.3.4')]))->serializeToPresentationFormat());
        self::assertEquals("\x01\x02\x03\x04",(new A([new IPv4Address('1.2.3.4')]))->serializeToWireFormat());
    }

}