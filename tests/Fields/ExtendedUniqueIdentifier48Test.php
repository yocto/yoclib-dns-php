<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\ExtendedUniqueIdentifier48;

class ExtendedUniqueIdentifier48Test extends TestCase{

    /**
     * @return void
     */
    public function testConstructor(): void{
        self::assertInstanceOf(ExtendedUniqueIdentifier48::class,new ExtendedUniqueIdentifier48('00-00-5e-00-53-2a'));
    }

    /**
     * @return void
     */
    public function testConstructorInvalid(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('A EUI48 should be 6 hexadecimal character pairs, separated by hyphens.');

        new ExtendedUniqueIdentifier48('00:00-5e-00-53-2a');
    }

    /**
     * @return void
     */
    public function testGetValue(): void{
        self::assertSame('00-00-5e-00-53-2a',(new ExtendedUniqueIdentifier48('00-00-5e-00-53-2a'))->getValue());
    }

    /**
     * @return void
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('00-00-5e-00-53-2a',(new ExtendedUniqueIdentifier48('00-00-5e-00-53-2a'))->serializeToPresentationFormat());
    }

    /**
     * @return void
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x00\x5E\x00\x53\x2A",(new ExtendedUniqueIdentifier48('00-00-5e-00-53-2a'))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(6,ExtendedUniqueIdentifier48::calculateLength("\x00\x00\x5E\x00\x53\x2A"));
        self::assertSame(6,ExtendedUniqueIdentifier48::calculateLength("\x00\x00\x5E\x00\x53\x2AtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame('00-00-5e-00-53-2a',ExtendedUniqueIdentifier48::deserializeFromPresentationFormat('00-00-5e-00-53-2a')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame('00-00-5e-00-53-2a',ExtendedUniqueIdentifier48::deserializeFromWireFormat("\x00\x00\x5E\x00\x53\x2A")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary EUI48 address should be 6 octets.');

        ExtendedUniqueIdentifier48::deserializeFromWireFormat("\x00\x00\x5E\x00\x53");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary EUI48 address should be 6 octets.');

        ExtendedUniqueIdentifier48::deserializeFromWireFormat("\x00\x00\x5E\x00\x53\x2A\x00\x00\x00\x00");
    }

}