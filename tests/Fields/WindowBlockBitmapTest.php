<?php
namespace YOCLIB\DNS\Tests\Fields;

use PHPUnit\Framework\TestCase;

use YOCLIB\DNS\Exceptions\DNSFieldException;
use YOCLIB\DNS\Exceptions\DNSMnemonicException;
use YOCLIB\DNS\Fields\WindowBlockBitmap;
use YOCLIB\DNS\MnemonicMapper;

class WindowBlockBitmapTest extends TestCase{

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructor(): void{
        self::assertInstanceOf(WindowBlockBitmap::class,new WindowBlockBitmap([1,2,3,4]));
        self::assertInstanceOf(WindowBlockBitmap::class,new WindowBlockBitmap([1,2,3,4,5]));
        self::assertInstanceOf(WindowBlockBitmap::class,new WindowBlockBitmap([1,2,3,4,5,6]));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorDuplicateBits(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('No duplicate bits allowed.');

        new WindowBlockBitmap([1,2,3,3,4,5,6]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorNonIntegerBits(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Only integers allowed.');

        new WindowBlockBitmap([1,2,'3',4,5,6]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testConstructorNegativeIntegers(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Only positive integers allowed.');

        new WindowBlockBitmap([1,2,-3,4,5,6]);
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testGetValue(): void{
        self::assertSame([1,2,3,4],(new WindowBlockBitmap([1,2,3,4]))->getValue());
        self::assertSame([1,2,3,4,5],(new WindowBlockBitmap([1,2,3,4,5]))->getValue());
        self::assertSame([1,2,3,4,5,6],(new WindowBlockBitmap([1,2,3,4,5,6]))->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     */
    public function testSerializeToPresentationFormat(): void{
        $mapper = new MnemonicMapper([
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5,
            'SIX' => 6,
        ]);

        self::assertSame('ONE TWO THREE FOUR',(new WindowBlockBitmap([1,2,3,4]))->serializeToPresentationFormat($mapper));
        self::assertSame('ONE TWO THREE FOUR FIVE',(new WindowBlockBitmap([1,2,3,4,5]))->serializeToPresentationFormat($mapper));
        self::assertSame('ONE TWO THREE FOUR FIVE SIX',(new WindowBlockBitmap([1,2,3,4,5,6]))->serializeToPresentationFormat($mapper));
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testSerializeToWireFormat(): void{
        self::assertSame("\x00\x01\x78",(new WindowBlockBitmap([1,2,3,4]))->serializeToWireFormat());
        self::assertSame("\x00\x01\x7C",(new WindowBlockBitmap([1,2,3,4,5]))->serializeToWireFormat());
        self::assertSame("\x00\x01\x7E",(new WindowBlockBitmap([1,2,3,4,5,6]))->serializeToWireFormat());
    }

    /**
     * @return void
     */
    public function testCalculateLength(): void{
        self::assertSame(1,WindowBlockBitmap::calculateLength("\x1E"));
        self::assertSame(14,WindowBlockBitmap::calculateLength("\x1EtrailingBytes"));
        self::assertSame(1,WindowBlockBitmap::calculateLength("\x3E"));
        self::assertSame(14,WindowBlockBitmap::calculateLength("\x3EtrailingBytes"));
        self::assertSame(1,WindowBlockBitmap::calculateLength("\x7E"));
        self::assertSame(14,WindowBlockBitmap::calculateLength("\x7EtrailingBytes"));
    }

    /**
     * @return void
     * @throws DNSFieldException
     * @throws DNSMnemonicException
     */
    public function testDeserializeFromPresentationFormat(): void{
        $mapper = new MnemonicMapper([
            'ONE' => 1,
            'TWO' => 2,
            'THREE' => 3,
            'FOUR' => 4,
            'FIVE' => 5,
            'SIX' => 6,
        ]);

        self::assertSame([1,2,3,4],WindowBlockBitmap::deserializeFromPresentationFormat('ONE TWO THREE FOUR',$mapper)->getValue());
        self::assertSame([1,2,3,4,5],WindowBlockBitmap::deserializeFromPresentationFormat('ONE TWO THREE FOUR FIVE',$mapper)->getValue());
        self::assertSame([1,2,3,4,5,6],WindowBlockBitmap::deserializeFromPresentationFormat('ONE TWO THREE FOUR FIVE SIX',$mapper)->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormat(): void{
        self::assertSame([1,2,3,4],WindowBlockBitmap::deserializeFromWireFormat("\x00\x01\x78")->getValue());
        self::assertSame([1,2,3,4,5],WindowBlockBitmap::deserializeFromWireFormat("\x00\x01\x7C")->getValue());
        self::assertSame([1,2,3,4,5,6],WindowBlockBitmap::deserializeFromWireFormat("\x00\x01\x7E")->getValue());
    }

    /**
     * @return void
     * @throws DNSFieldException
     */
    public function testDeserializeFromWireFormatNoLength(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Too less data to read window block length.');

        WindowBlockBitmap::deserializeFromWireFormat("\x00");
    }

    public function testDeserializeFromWireFormatTooLessData(): void{
        self::expectException(DNSFieldException::class);
        self::expectExceptionMessage('Too less data to read window block bytes.');

        WindowBlockBitmap::deserializeFromWireFormat("\x00\x01");
    }

}