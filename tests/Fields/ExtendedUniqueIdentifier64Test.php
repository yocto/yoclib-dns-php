<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\ExtendedUniqueIdentifier64;

class ExtendedUniqueIdentifier64Test extends TestCase{

    /**
     * @return void
     */
    public function testConstructor(): void{
        self::assertInstanceOf(ExtendedUniqueIdentifier64::class,new ExtendedUniqueIdentifier64('00-00-5e-ef-10-00-00-2a'));
    }

    /**
     * @return void
     */
    public function testConstructorInvalid(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('A EUI64 should be 8 hexadecimal character pairs, separated by hyphens.');

        new ExtendedUniqueIdentifier64('00:00-5e-ef-10-00-00-2a');
    }

    /**
     * @return void
     */
    public function testGetValue(): void{
        self::assertSame('00-00-5e-ef-10-00-00-2a',(new ExtendedUniqueIdentifier64('00-00-5e-ef-10-00-00-2a'))->getValue());
    }

    /**
     * @return void
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('00-00-5e-ef-10-00-00-2a',(new ExtendedUniqueIdentifier64('00-00-5e-ef-10-00-00-2a'))->serializeToPresentationFormat());
    }

    /**
     * @return void
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x00\x5E\xEF\x10\x00\x00\x2A",(new ExtendedUniqueIdentifier64('00-00-5e-ef-10-00-00-2a'))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(8,ExtendedUniqueIdentifier64::calculateLength("\x00\x00\x5E\xEF\x10\x00\x00\x2A"));
        self::assertSame(8,ExtendedUniqueIdentifier64::calculateLength("\x00\x00\x5E\xEF\x10\x00\x00\x2AtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame('00-00-5e-ef-10-00-00-2a',ExtendedUniqueIdentifier64::deserializeFromPresentationFormat('00-00-5e-ef-10-00-00-2a')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame('00-00-5e-ef-10-00-00-2a',ExtendedUniqueIdentifier64::deserializeFromWireFormat("\x00\x00\x5E\xEF\x10\x00\x00\x2A")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary EUI64 address should be 8 octets.');

        ExtendedUniqueIdentifier64::deserializeFromWireFormat("\x00\x00\x5E\xEF\x10\x00");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary EUI64 address should be 8 octets.');

        ExtendedUniqueIdentifier64::deserializeFromWireFormat("\x00\x00\x5E\xEF\x10\x00\x00\x2A\x00\x00\x00\x00");
    }

}