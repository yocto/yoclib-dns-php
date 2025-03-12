<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\UnsignedInteger64;

class UnsignedInteger64Test extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(UnsignedInteger64::class,new UnsignedInteger64(0));
        self::assertInstanceOf(UnsignedInteger64::class,new UnsignedInteger64(9187201950435737471));
        self::assertInstanceOf(UnsignedInteger64::class,new UnsignedInteger64(gmp_init('9259542123273814144')));
        self::assertInstanceOf(UnsignedInteger64::class,new UnsignedInteger64(gmp_init('18446744073709551615')));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorTooLow(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt64 should be in the range of 0 and 18446744073709551615.');

        new UnsignedInteger64(gmp_init('-36893488147419103232'));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorTooHigh(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt64 should be in the range of 0 and 18446744073709551615.');

        new UnsignedInteger64(gmp_init('36893488147419103232'));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSameGMP(0,(new UnsignedInteger64(0))->getValue());
        self::assertSameGMP(9187201950435737471,(new UnsignedInteger64(9187201950435737471))->getValue());
        self::assertSameGMP(gmp_init('9259542123273814144'),(new UnsignedInteger64(gmp_init('9259542123273814144')))->getValue());
        self::assertSameGMP(gmp_init('18446744073709551615'),(new UnsignedInteger64(gmp_init('18446744073709551615')))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('0',(new UnsignedInteger64(0))->serializeToPresentationFormat());
        self::assertSame('9187201950435737471',(new UnsignedInteger64(9187201950435737471))->serializeToPresentationFormat());
        self::assertSame('9259542123273814144',(new UnsignedInteger64(gmp_init('9259542123273814144')))->serializeToPresentationFormat());
        self::assertSame('18446744073709551615',(new UnsignedInteger64(gmp_init('18446744073709551615')))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x00\x00\x00\x00\x00\x00\x00",(new UnsignedInteger64(0))->serializeToWireFormat());
        self::assertSame("\x7F\x7F\x7F\x7F\x7F\x7F\x7F\x7F",(new UnsignedInteger64(9187201950435737471))->serializeToWireFormat());
        self::assertSame("\x80\x80\x80\x80\x80\x80\x80\x80",(new UnsignedInteger64(gmp_init('9259542123273814144')))->serializeToWireFormat());
        self::assertSame("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF",(new UnsignedInteger64(gmp_init('18446744073709551615')))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(8,UnsignedInteger64::calculateLength("\x00\x00\x00\x00\x00\x00\x00\x00"));
        self::assertSame(8,UnsignedInteger64::calculateLength("\x00\x00\x00\x00\x00\x00\x00\x00trailingBytes"));
        self::assertSame(8,UnsignedInteger64::calculateLength("\x7F\x7F\x7F\x7F\x7F\x7F\x7F\x7F"));
        self::assertSame(8,UnsignedInteger64::calculateLength("\x7F\x7F\x7F\x7F\x7F\x7F\x7F\x7FtrailingBytes"));
        self::assertSame(8,UnsignedInteger64::calculateLength("\x80\x80\x80\x80\x80\x80\x80\x80"));
        self::assertSame(8,UnsignedInteger64::calculateLength("\x80\x80\x80\x80\x80\x80\x80\x80trailingBytes"));
        self::assertSame(8,UnsignedInteger64::calculateLength("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF"));
        self::assertSame(8,UnsignedInteger64::calculateLength("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFFtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSameGMP(0,UnsignedInteger64::deserializeFromPresentationFormat('0')->getValue());
        self::assertSameGMP(9187201950435737471,UnsignedInteger64::deserializeFromPresentationFormat('9187201950435737471')->getValue());
        self::assertSameGMP(gmp_init('9259542123273814144'),UnsignedInteger64::deserializeFromPresentationFormat('9259542123273814144')->getValue());
        self::assertSameGMP(gmp_init('18446744073709551615'),UnsignedInteger64::deserializeFromPresentationFormat('18446744073709551615')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormatNoInteger(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt64 should only contain digits.');

        UnsignedInteger64::deserializeFromPresentationFormat('abc');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSameGMP(0,UnsignedInteger64::deserializeFromWireFormat("\x00\x00\x00\x00\x00\x00\x00\x00")->getValue());
        self::assertSameGMP(9187201950435737471,UnsignedInteger64::deserializeFromWireFormat("\x7F\x7F\x7F\x7F\x7F\x7F\x7F\x7F")->getValue());
        self::assertSameGMP(gmp_init('9259542123273814144'),UnsignedInteger64::deserializeFromWireFormat("\x80\x80\x80\x80\x80\x80\x80\x80")->getValue());
        self::assertSameGMP(gmp_init('18446744073709551615'),UnsignedInteger64::deserializeFromWireFormat("\xFF\xFF\xFF\xFF\xFF\xFF\xFF\xFF")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt64 should be 8 octets.');

        UnsignedInteger64::deserializeFromWireFormat("\xAA\xBB\xCC\xDD\xEE\xFF");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt64 should be 8 octets.');

        UnsignedInteger64::deserializeFromWireFormat("\xAA\xBB\xCC\xDD\xEE\xFF\x00\x11\x22\x33");
    }

    /**
     * @param $expected
     * @param $actual
     * @param string $message
     * @return void
     */
    protected static function assertSameGMP($expected,$actual,string $message = ''): void{
        self::assertSame(0,gmp_cmp($expected,$actual),$message);
    }

}