<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Fields\UnsignedInteger48;

class UnsignedInteger48Test extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(UnsignedInteger48::class,new UnsignedInteger48(0));
        self::assertInstanceOf(UnsignedInteger48::class,new UnsignedInteger48(140185576636287));
        self::assertInstanceOf(UnsignedInteger48::class,new UnsignedInteger48(141289400074368));
        self::assertInstanceOf(UnsignedInteger48::class,new UnsignedInteger48(281474976710655));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorTooLow(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt48 should be in the range of 0 and 281474976710655.');

        new UnsignedInteger48(-562949953421312);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorTooHigh(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt48 should be in the range of 0 and 281474976710655.');

        new UnsignedInteger48(562949953421312);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame(0,(new UnsignedInteger48(0))->getValue());
        self::assertSame(140185576636287,(new UnsignedInteger48(140185576636287))->getValue());
        self::assertSame(141289400074368,(new UnsignedInteger48(141289400074368))->getValue());
        self::assertSame(281474976710655,(new UnsignedInteger48(281474976710655))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToPresentationFormat(): void{
        self::assertSame('0',(new UnsignedInteger48(0))->serializeToPresentationFormat());
        self::assertSame('140185576636287',(new UnsignedInteger48(140185576636287))->serializeToPresentationFormat());
        self::assertSame('141289400074368',(new UnsignedInteger48(141289400074368))->serializeToPresentationFormat());
        self::assertSame('281474976710655',(new UnsignedInteger48(281474976710655))->serializeToPresentationFormat());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x00\x00\x00\x00\x00",(new UnsignedInteger48(0))->serializeToWireFormat());
        self::assertSame("\x7F\x7F\x7F\x7F\x7F\x7F",(new UnsignedInteger48(140185576636287))->serializeToWireFormat());
        self::assertSame("\x80\x80\x80\x80\x80\x80",(new UnsignedInteger48(141289400074368))->serializeToWireFormat());
        self::assertSame("\xFF\xFF\xFF\xFF\xFF\xFF",(new UnsignedInteger48(281474976710655))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(6,UnsignedInteger48::calculateLength("\x00\x00\x00\x00\x00\x00"));
        self::assertSame(6,UnsignedInteger48::calculateLength("\x00\x00\x00\x00\x00\x00trailingBytes"));
        self::assertSame(6,UnsignedInteger48::calculateLength("\x7F\x7F\x7F\x7F\x7F\x7F"));
        self::assertSame(6,UnsignedInteger48::calculateLength("\x7F\x7F\x7F\x7F\x7F\x7FtrailingBytes"));
        self::assertSame(6,UnsignedInteger48::calculateLength("\x80\x80\x80\x80\x80\x80"));
        self::assertSame(6,UnsignedInteger48::calculateLength("\x80\x80\x80\x80\x80\x80trailingBytes"));
        self::assertSame(6,UnsignedInteger48::calculateLength("\xFF\xFF\xFF\xFF\xFF\xFF"));
        self::assertSame(6,UnsignedInteger48::calculateLength("\xFF\xFF\xFF\xFF\xFF\xFFtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormat(): void{
        self::assertSame(0,UnsignedInteger48::deserializeFromPresentationFormat('0')->getValue());
        self::assertSame(140185576636287,UnsignedInteger48::deserializeFromPresentationFormat('140185576636287')->getValue());
        self::assertSame(141289400074368,UnsignedInteger48::deserializeFromPresentationFormat('141289400074368')->getValue());
        self::assertSame(281474976710655,UnsignedInteger48::deserializeFromPresentationFormat('281474976710655')->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromPresentationFormatNoInteger(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Human readable UInt48 should only contain digits.');

        UnsignedInteger48::deserializeFromPresentationFormat('abc');
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame(0,UnsignedInteger48::deserializeFromWireFormat("\x00\x00\x00\x00\x00\x00")->getValue());
        self::assertSame(140185576636287,UnsignedInteger48::deserializeFromWireFormat("\x7F\x7F\x7F\x7F\x7F\x7F")->getValue());
        self::assertSame(141289400074368,UnsignedInteger48::deserializeFromWireFormat("\x80\x80\x80\x80\x80\x80")->getValue());
        self::assertSame(281474976710655,UnsignedInteger48::deserializeFromWireFormat("\xFF\xFF\xFF\xFF\xFF\xFF")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt48 should be 6 octets.');

        UnsignedInteger48::deserializeFromWireFormat("\xAA\xBB\xCC");
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatTooMuchData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Binary UInt48 should be 6 octets.');

        UnsignedInteger48::deserializeFromWireFormat("\xAA\xBB\xCC\xDD\xEE\xFF\x00\x11");
    }

}